package com.recessg_26;

import java.io.File;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.time.LocalDate;
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
        String email;
        String companyName;
        String pdfPath;

        CompanyData(int companyId, String companyName, String pdfPath, String email) {
            this.companyId = companyId;
            this.companyName = companyName;
            this.pdfPath = pdfPath;
            this.email = email;
        }
    }

    // Data class to hold extracted PDF information
    static class CompanyPdfData{
        int companyId;
        String name;
        String email;
        String reference1Name;
        String reference2Name;
        String reference3Name;
        long annualRevenue;
        long cashFlowYear1;
        long cashFlowYear2;
        String certification1Type;
        String certification2Type;
        String certification1Expiry;
        String certification2Expiry;
        double debtToEquityRatio;
        double creditScore;
        String legalDisputes;
        String environmentalCompliance;
        String reference1Relationship;
    }

    // Method to execute the query for pending companies
    static List<CompanyData> executeQuery(Connection conn) throws SQLException {
        String query = "SELECT company_id, company_name, pdf_path, email FROM companies WHERE acceptance_status = ?";
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
                    String email = rs.getString("email");

                    companies.add(new CompanyData(companyId, companyName, pdfPath, email));
                    
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

    static CompanyPdfData parsePdfData(String text, int companyId, String companyName, String email) {
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
                "Environmental Compliance:",
                "Reference 1 - Relationship:"
        };

        CompanyPdfData data = new CompanyPdfData();
        // Extract each field from the text

        data.companyId = companyId;
        data.name = companyName;
        data.email = email;
        data.annualRevenue = safeParseLong(extract(text, fields[0]));
        data.debtToEquityRatio = safeParseDouble(extract(text, fields[1]));
        data.cashFlowYear1 = safeParseLong(extract(text, fields[2]));
        data.cashFlowYear2 = safeParseLong(extract(text, fields[3]));
        data.creditScore = safeParseDouble(extract(text, fields[4]));
        data.reference1Name = extract(text, fields[5]);
        data.reference2Name = extract(text, fields[6]);
        data.reference3Name = extract(text, fields[7]);
        data.legalDisputes = extract(text, fields[8]);
        data.certification1Type = extract(text, fields[9]);
        data.certification2Type = extract(text, fields[10]);
        data.certification1Expiry = extract(text, fields[11]);
        data.certification2Expiry = extract(text, fields[12]);
        data.environmentalCompliance = extract(text, fields[13]);
        data.reference1Relationship = extract(text, fields[14]);

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

     // Financial Risk Rating calculation
    static double calculateFinancialRiskRating(CompanyPdfData company) {
        double score = 0.0;
        
        // Annual Revenue
        if (company.annualRevenue > 500_000_000) score += 3.0;
        else if (company.annualRevenue > 100_000_000) score += 2.5;
        else if (company.annualRevenue > 20_000_000) score += 2.0;
        else if (company.annualRevenue > 5_000_000) score += 1.5;
        else if (company.annualRevenue > 2_000_000) score += 1.0;
        else if (company.annualRevenue < 0L) score += 0.0;
        else score += 0.5;

        // Debt-to-Equity Ratio
        if (company.debtToEquityRatio < 0.5) score += 2.5;
        else if (company.debtToEquityRatio <= 1.0) score += 2.0;
        else if (company.debtToEquityRatio <= 2.0) score += 1.5;
        else if (company.debtToEquityRatio <= 3.0) score += 1.0;
        else if (company.debtToEquityRatio <= 4.0) score += 0.5;
        else if (company.debtToEquityRatio < 0.0) score += 0.0;


        // Cash Flow Year 1
        if (company.cashFlowYear1 > 5_000_000) score += 2.5;
        else if (company.cashFlowYear1 > 1_000_000) score += 2.0;
        else if (company.cashFlowYear1 > 0) score += 1.5;
        else if (company.cashFlowYear1 == -1L) score += 0.0;
        else if (company.cashFlowYear1 > -1_000_000) score += 1.0;
        else if (company.cashFlowYear1 > -5_000_000) score += 0.5;

        // Cash Flow Year 2)
        if (company.cashFlowYear2 > 0) score += 1.0;
        

        // Credit Score
        if (company.creditScore > 80) score += 1.0;
        else if (company.creditScore >= 60) score += 0.8;
        else if (company.creditScore >= 50) score += 0.6;
        else if (company.creditScore >= 40) score += 0.4;
        else if (company.creditScore < 0) score += 0.0;
        else score += 0.2;

        // Penalty for high revenue with high debt
        if (company.annualRevenue > 100_000_000 && company.debtToEquityRatio > 3.0) score -= 0.5;

        return Math.max(0.0, Math.min(10.0, score));
    }

    // Reputational Risk Rating calculation
    static double calculateReputationalRiskRating(CompanyPdfData company) {
        double score = 0.0;
        
        // References
        if (company.reference1Name != null && !company.reference1Name.isEmpty() && 
            !company.reference1Name.equalsIgnoreCase("Not found") &&
             !company.reference1Name.equalsIgnoreCase("Not provided"))score += 3.0;
        if (company.reference2Name != null && !company.reference2Name.isEmpty() && 
            !company.reference2Name.equalsIgnoreCase("Not found") &&
             !company.reference2Name.equalsIgnoreCase("Not provided")) score += 2.0;
        if (company.reference3Name != null && !company.reference3Name.isEmpty() && 
            !company.reference3Name.equalsIgnoreCase("Not found") && 
            !company.reference3Name.equalsIgnoreCase("Not provided")) score += 1.0;

        // Legal Disputes
        if (company.legalDisputes.equalsIgnoreCase("No") || company.legalDisputes.equalsIgnoreCase("False")) score += 4.0;

        // Reference Source Quality
        boolean isReference1Reputable = company.reference1Relationship.toLowerCase().contains("trader") ||
                                       company.reference1Relationship.toLowerCase().contains("cooperative");
        if (isReference1Reputable) score += 1.0;

        return Math.max(0.0, Math.min(10.0, score));
    }

    // Compliance Risk Rating calculation
    static double calculateComplianceRiskRating(CompanyPdfData company) {
        double score = 0.0;
        
        // Certificates
        if (company.certification1Type != null && !company.certification1Type.isEmpty() && 
            !company.certification1Type.equalsIgnoreCase("Not found") &&
            !company.certification1Type.equalsIgnoreCase("Not provided")) score += 2.0;
        if (company.certification2Type != null && !company.certification2Type.isEmpty() && 
            !company.certification2Type.equalsIgnoreCase("Not found") &&
            !company.certification2Type.equalsIgnoreCase("Not provided")) score += 1.0;

        // Environmental Compliance
        if (company.environmentalCompliance.equalsIgnoreCase("Yes") ||
          company.environmentalCompliance.equalsIgnoreCase("True")) score += 3.0;

        // Certificate Validity
        LocalDate today = LocalDate.now();
        if (company.certification1Expiry != null && !company.certification1Expiry.isEmpty() &&
            !company.certification1Expiry.equalsIgnoreCase("Not found") &&
            !company.certification1Expiry.equalsIgnoreCase("Not provided")) {
            LocalDate expiry1 = LocalDate.parse(company.certification1Expiry);
            long monthsUntilExpiry = ChronoUnit.MONTHS.between(today, expiry1);
            if (monthsUntilExpiry > 12) score += 2.0;
            else if (monthsUntilExpiry >= 6) score += 1.0;
        }
        if (company.certification2Expiry != null && !company.certification2Expiry.isEmpty() &&
            !company.certification2Expiry.equalsIgnoreCase("Not found") &&
            !company.certification2Expiry.equalsIgnoreCase("Not provided")) {
            LocalDate expiry2 = LocalDate.parse(company.certification2Expiry);
            long monthsUntilExpiry = ChronoUnit.MONTHS.between(today, expiry2);
            if (monthsUntilExpiry > 12) score += 2.0;
            else if (monthsUntilExpiry >= 6) score += 1.0;
        }

        // Environmental Certification Bonus
        boolean isCert1Environmental = company.certification1Type.toLowerCase().contains("fair trade") ||
                                      company.certification1Type.toLowerCase().contains("organic");
        if (isCert1Environmental) score += 1.0;

        return Math.max(0.0, Math.min(10.0, score));
    }

    // Method to update database with risk ratings
    private static void updateCompanyRiskRatings(Connection conn, CompanyPdfData company, 
            double financialRisk, double reputationalRisk, double complianceRisk, String status) throws SQLException {
        String updateQuery = "UPDATE companies SET financial_risk_rating = ?, reputational_risk_rating = ?, " +
                           "compliance_risk_rating = ?, acceptance_status = ? WHERE company_id = ?";
        
        try (PreparedStatement pstmt = conn.prepareStatement(updateQuery)) {
            pstmt.setDouble(1, financialRisk);
            pstmt.setDouble(2, reputationalRisk);
            pstmt.setDouble(3, complianceRisk);
            pstmt.setString(4, status);
            pstmt.setInt(5, company.companyId);
            
            int rowsAffected = pstmt.executeUpdate();
            if (rowsAffected > 0) {
                System.out.println("Successfully updated company ID: " + company.companyId + 
                                 " with status: " + status);
            }
        }
    }



    // Method to process all PDFs
    static void processPdfs(List<CompanyData> companies) {
        if (companies.isEmpty()) {
            System.out.println("No PDFs to process.");
            return;
        }
        System.out.println("=== Processing PDFs ===");

        // Create database connection for updates
        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/coffeechain", "root", "")) {
        for (CompanyData company : companies) {
            try {
                System.out.println("Processing PDF for: " + company.companyName);

                // Extract text from PDF
                String pdfText = extractTextFromPdf(company.pdfPath);

                // Parse the extracted text
                CompanyPdfData pdfData = parsePdfData(pdfText,company.companyId,company.companyName,company.email);
                 // Calculate risk ratings
                    double financialRiskRating = calculateFinancialRiskRating(pdfData);
                    double reputationalRiskRating = calculateReputationalRiskRating(pdfData);
                    double complianceRiskRating = calculateComplianceRiskRating(pdfData);
                    double totalScore = financialRiskRating + reputationalRiskRating + complianceRiskRating;

                     // Determine acceptance status
                    String status = totalScore >= 20.0 ? "visit_scheduled" : "rejected";

                    // Update database
                    updateCompanyRiskRatings(conn, pdfData, financialRiskRating, 
                                           reputationalRiskRating, complianceRiskRating, status);
                    
                    // Send email notification
                    if (status.equals("visit_scheduled")) {
                        Email.visitScheduledEmail(pdfData);
                    } else {
                        Email.rejectEmail(pdfData);
                    }

            } catch (Exception e) {
                System.out.println("Error processing company " + company.companyName + ": " + e.getMessage());
                    e.printStackTrace();
            }
        }
    }catch (SQLException e) {
                  System.out.println("Database error while processing PDFs: " + e.getMessage());
                    e.printStackTrace();
                }
    }

     static int safeParseInt(String value) {
        try {
            return Integer.parseInt(value);
        } catch (NumberFormatException e) {
            return -1;
        }
    }

    static long safeParseLong(String value) {
        try {
            return Long.parseLong(value);
        } catch (NumberFormatException e) {
            return -1L;
        }
    }

    static double safeParseDouble(String value) {
        try {
            return Double.parseDouble(value);
        } catch (NumberFormatException e) {
            return -1.0;
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
        
        return ChronoUnit.SECONDS.between(now, endOfDay);
    }
    
    // Method to schedule the task
    private static void scheduleEndOfDayTask() {
        Runnable task = () -> {
            try {
                System.out.println("Running scheduled database task...");
                List<CompanyData> companies = createConnection();
                processPdfs(companies);
            } catch (SQLException | ClassNotFoundException e) {
                e.printStackTrace();
            }
        };
        
        // Calculate initial delay until end of day
        long initialDelay = getDelayUntilEndOfDay();
        
        // Schedule the task to run at end of day, then repeat every 24 hours
        scheduler.scheduleWithFixedDelay(task, initialDelay, 24 * 60 *60, TimeUnit.SECONDS);
        
        System.out.println("Database task scheduled to run at end of day (11:59 PM)");
        System.out.println("Initial delay: " + initialDelay + " seconds");
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
        
        long initialDelay = 2; // 5 minutes from now
        long period = 2; // Repeat every 5 minutes
        scheduler.scheduleWithFixedDelay(task, initialDelay, period, TimeUnit.MINUTES);

        System.out.println("Database task scheduled to run every 5 minutes");
        System.out.println("Initial delay: " + initialDelay + " minutes");
    }

    public static void main(String[] args) {
        // Run once immediately for testing
        try {
            System.out.println("Running initial database connection...");
            List<CompanyData> companies = createConnection();
            processPdfs(companies);
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