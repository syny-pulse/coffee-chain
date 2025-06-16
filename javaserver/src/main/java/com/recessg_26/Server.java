package com.recessg_26;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.time.LocalDateTime;
import java.time.temporal.ChronoUnit;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

class Server {
    private static final ScheduledExecutorService scheduler = Executors.newScheduledThreadPool(1);

     // Method to execute the query for pending companies
    static void executeQuery(Connection conn) throws SQLException {
        String query = "SELECT company_id, company_name, pdf_path FROM companies WHERE acceptance_status = ?";
        
        try (PreparedStatement pstmt = conn.prepareStatement(query)) {
            pstmt.setString(1, "pending");
            
            try (ResultSet rs = pstmt.executeQuery()) {
                System.out.println("=== Companies with Pending Status ===");
                int count = 0;
                
                while (rs.next()) {
                    int companyId = rs.getInt("company_id");
                    String companyName = rs.getString("company_name");
                    String pdfPath = rs.getString("pdf_path");
                    
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
        }
    }
    
    static void createConnection() throws SQLException, ClassNotFoundException {
        Connection conn = null;
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/coffeechain", "root", "");
            System.out.println("Database Connection success at: " + LocalDateTime.now());
            
            // Execute the query for pending companies
            executeQuery(conn);
            
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
                createConnection();
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