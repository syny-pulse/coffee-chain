package com.recessg_26;

import java.io.File;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.time.LocalDateTime;
import java.time.temporal.ChronoUnit;
import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.apache.pdfbox.Loader;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;

class Server {
    private static final ScheduledExecutorService scheduler = Executors.newScheduledThreadPool(1);

    // Data class to hold company data
    static class CompanyData {
        int companyId;
        String companyName;
        String pdfPath;

        CompanyData(int companyId, String companyName, String pdfPath) {
            this.companyId = companyId;
            this.companyName = companyName;
            this.pdfPath = pdfPath;
        }
    }

    // Data class to hold extracted PDF information
    static class CompanyPdfData{
        String reference1Name;
        String reference2Name;
        String reference3Name;
        String annualRevenue;
        String cashFlowYear1;
        String cashFlowYear2;
        String certification1Type;
        String certification2Type;
        String certification1Expiry;
        String certification2Expiry;
        String debtToEquityRatio;
        String creditScore;
        String legalDisputes;
        String environmentalCompliance;
    }

    // Method to execute the query for pending companies
    static List<CompanyData> executeQuery(Connection conn) throws SQLException {
        String query = "SELECT company_id, company_name, pdf_path FROM companies WHERE acceptance_status = ?";
        List<CompanyData> companies = new ArrayList<>();

        try (PreparedStatement pstmt = conn.prepareStatement(query)) {
            pstmt.setString(1, "pending");
            
            try (ResultSet rs = pstmt.executeQuery()) {
                System.out.println("=== Companies with Pending Status ===");
                int count = 0;
                
                while (rs.next()) {
                    int companyId = rs.getInt("company_id");
                    String companyName = rs.getString("company_name");
                    String pdfPath = rs.getString("pdf_path");

                    companies.add(new CompanyData(companyId, companyName, pdfPath));
                    
                    System.out.println("Company ID: " + companyId);
                    System.out.println("Company Name: " + companyName);
                    System.out.println("PDF Path: " + pdfPath);
                    System.out.println("---");
                    count++;
                }
                
                if (count == 0) {
                    System.out.println("No companies found with pending status.");
                } else {
                    System.out.println("Total companies with pending status: " + count);
                }
                System.out.println("=== End of Query Results ===");
            }
            return companies;
        }
    }

    static String extractTextFromPdf(String pdfPath) throws IOException {
        // Verify file exists
        File pdfFile = new File(pdfPath);
        if (!pdfFile.exists()) {
             throw new IOException("PDF file not found: " + pdfPath);
        }

        try (PDDocument document = Loader.loadPDF(pdfFile)) {
            if (document.isEncrypted()) {
                throw new IOException("Error: PDF document is encrypted and cannot be processed.");
            }

            PDFTextStripper stripper = new PDFTextStripper();
            String text = stripper.getText(document);// Verify file exists
        
            return text;

        }
    }

    static CompanyPdfData parsePdfData(String text) {
        // List of fields to extract
        String[] fields = {
                "Annual Revenue (UGX):", 
                "Debt-to-Equity Ratio:", 
                "Cash Flow Summary - Year 1:", 
                "Cash Flow Summary - Year 2:",
                "Credit Score:", 
                "Reference 1 - Name:",
                "Reference 2 - Name:", 
                "Reference 3 - Name:", 
                "Legal Disputes:",
                "Certification 1 - Type:",
                "Certification 2 - Type:",
                "Certification 1 - Expiry:",
                "Certification 2 - Expiry:", 
                "Environmental Compliance:"
        };

        CompanyPdfData data = new CompanyPdfData();
        // Extract each field from the text

        data.annualRevenue = extract(text, fields[0]);
        data.debtToEquityRatio = extract(text, fields[1]);
        data.cashFlowYear1 = extract(text, fields[2]);
        data.cashFlowYear2 = extract(text, fields[3]);
        data.creditScore = extract(text, fields[4]);
        data.reference1Name = extract(text, fields[5]);
        data.reference2Name = extract(text, fields[6]);
        data.reference3Name = extract(text, fields[7]);
        data.legalDisputes = extract(text, fields[8]);
        data.certification1Type = extract(text, fields[9]);
        data.certification2Type = extract(text, fields[10]);
        data.certification1Expiry = extract(text, fields[11]);
        data.certification2Expiry = extract(text, fields[12]);
        data.environmentalCompliance = extract(text, fields[13]);

        return data;

    }

    static String extract(String text, String label) {
        // Use multiline flag to handle labels across lines
        Pattern pattern = Pattern.compile(Pattern.quote(label) + "\\s*(.*?)(?=\\n\\S|$)", Pattern.DOTALL);
        Matcher matcher = pattern.matcher(text);
        if (matcher.find()) {
            String value = matcher.group(1).trim();
            return value.isEmpty() ? "Not found" : value;
        
        } else {
            return "Not found";
        }
    }


    // Method to process all PDFs
    static void processPdfs(List<CompanyData> companies) {
        if (companies.isEmpty()) {
            System.out.println("No PDFs to process.");
            return;
        }
        System.out.println("=== Processing PDFs ===");
        for (CompanyData company : companies) {
            try {
                System.out.println("Processing PDF for: " + company.companyName);

                // Extract text from PDF
                String pdfText = extractTextFromPdf(company.pdfPath);

                // Parse the extracted text
                CompanyPdfData pdfData = parsePdfData(pdfText);



            } catch (Exception e) {
            }
        }
    }

    static List<CompanyData> createConnection() throws SQLException, ClassNotFoundException {
        Connection conn = null;
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/coffeechain", "root", "");
            System.out.println("Database Connection success at: " + LocalDateTime.now());
            
            // Execute the query for pending companies
            List<CompanyData> companies = executeQuery(conn);

            return companies;

        } finally {
            if (conn != null) {
                try {
                    conn.close();
                    System.out.println("Database connection closed");
                } catch (SQLException e) {
                    e.printStackTrace();
                }
            }
        }
    }
    
    // Method to calculate delay until end of day (e.g., 11:59 PM)
    private static long getDelayUntilEndOfDay() {
        LocalDateTime now = LocalDateTime.now();
        LocalDateTime endOfDay = now.toLocalDate().atTime(23, 59); // 11:59 PM
        
        if (now.isAfter(endOfDay)) {
            // If it's already past end of day, schedule for tomorrow
            endOfDay = endOfDay.plusDays(1);
        }
        
        return ChronoUnit.MINUTES.between(now, endOfDay);
    }
    
    // Method to schedule the task
    private static void scheduleEndOfDayTask() {
        Runnable task = () -> {
            try {
                System.out.println("Running scheduled database task...");
                createConnection();
            } catch (SQLException | ClassNotFoundException e) {
                e.printStackTrace();
            }
        };
        
        // Calculate initial delay until end of day
        long initialDelay = getDelayUntilEndOfDay();
        
        // Schedule the task to run at end of day, then repeat every 24 hours
        scheduler.scheduleAtFixedRate(task, initialDelay, 24 * 60, TimeUnit.MINUTES);
        
        System.out.println("Database task scheduled to run at end of day (11:59 PM)");
        System.out.println("Initial delay: " + initialDelay + " minutes");
    }

	// Method to schedule the task to run every 5 minutes
    private static void scheduleTask() {
        Runnable task = () -> {
            try {
                System.out.println("Running scheduled database task...");
                List<CompanyData> companies = createConnection();
                processPdfs(companies);
            } catch (SQLException | ClassNotFoundException e) {
                e.printStackTrace();
            }
        };
        
        long initialDelay = 5; // 5 minutes from now
        long period = 5; // Repeat every 5 minutes
        scheduler.scheduleAtFixedRate(task, initialDelay, period, TimeUnit.MINUTES);

        System.out.println("Database task scheduled to run every 5 minutes");
        System.out.println("Initial delay: " + initialDelay + " minutes");
    }

    public static void main(String[] args) {
        // Run once immediately for testing
        try {
            System.out.println("Running initial database connection...");
            createConnection();
        } catch (SQLException | ClassNotFoundException e) {
            e.printStackTrace();
        }
        
        // Schedule for end of day
        // scheduleEndOfDayTask();

		// Alternatively, you can schedule the task to run every 5 minutes
		 scheduleTask();
        
        // Keep the program running
        Runtime.getRuntime().addShutdownHook(new Thread(() -> {
            System.out.println("Shutting down scheduler...");
            scheduler.shutdown();
        }));
        
        // Keep main thread alive
        try {
            Thread.currentThread().join();
        } catch (InterruptedException e) {
            Thread.currentThread().interrupt();
        }
    }
}