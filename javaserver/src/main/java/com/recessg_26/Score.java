package com.recessg_26;

import java.time.LocalDate;
import java.time.temporal.ChronoUnit;

import com.recessg_26.Server.CompanyData;

public class Score {

    // Financial Risk Rating (from previous response)
    private static double calculateFinancialRiskRating(CompanyData company) {
        double score = 0.0;
        // Annual Revenue
        if (company.annualRevenue > 500_000_000) score += 3.0;
        else if (company.annualRevenue > 100_000_000) score += 2.5;
        else if (company.annualRevenue > 20_000_000) score += 2.0;
        else if (company.annualRevenue > 5_000_000) score += 1.5;
        else if (company.annualRevenue > 2_000_000) score += 1.0;
        else score += 0.5;

        // Debt-to-Equity Ratio
        if (company.debtToEquityRatio < 0.5) score += 2.5;
        else if (company.debtToEquityRatio <= 1.0) score += 2.0;
        else if (company.debtToEquityRatio <= 2.0) score += 1.5;
        else if (company.debtToEquityRatio <= 3.0) score += 1.0;
        else if (company.debtToEquityRatio <= 4.0) score += 0.5;

        // Cash Flow Year 1
        if (company.cashFlowYear1 > 5_000_000) score += 2.5;
        else if (company.cashFlowYear1 > 1_000_000) score += 2.0;
        else if (company.cashFlowYear1 > 0) score += 1.5;
        else if (company.cashFlowYear1 > -1_000_000) score += 1.0;
        else if (company.cashFlowYear1 > -5_000_000) score += 0.5;

        // Cash Flow Year 2 (optional)
        if (company.cashFlowYear2 != null && company.cashFlowYear2 > 0) score += 1.0;

        // Credit Score
        if (company.creditScore > 80) score += 1.0;
        else if (company.creditScore >= 60) score += 0.8;
        else if (company.creditScore >= 50) score += 0.6;
        else if (company.creditScore >= 40) score += 0.4;
        else score += 0.2;

        // Penalty for high revenue with high debt
        if (company.annualRevenue > 100_000_000 && company.debtToEquityRatio > 3.0) score -= 0.5;

        return Math.max(0.0, Math.min(10.0, score));
    }

    // Reputational Risk Rating (from previous response)
    private static double calculateReputationalRiskRating(CompanyData company) {
        double score = 0.0;
        // References
        score += 3.0; // Reference 1 mandatory
        if (company.reference2Name != null && !company.reference2Name.isEmpty()) score += 2.0;
        if (company.reference3Name != null && !company.reference3Name.isEmpty()) score += 1.0;

        // Legal Disputes
        if (!company.hasLegalDisputes) score += 4.0;

        // Reference Source Quality (simplified: assume reputable if from known entities)
        boolean isReference1Reputable = company.reference1Relationship.toLowerCase().contains("trader") ||
                                       company.reference1Relationship.toLowerCase().contains("cooperative");
        if (isReference1Reputable) score += 0.5;
        if (isReference1Reputable && (company.reference2Name != null || company.reference3Name != null)) score += 1.0;

        return Math.max(0.0, Math.min(10.0, score));
    }

    // Compliance Risk Rating (from previous response)
    private static double calculateComplianceRiskRating(CompanyData company) {
        double score = 0.0;
        // Certificates
        score += 2.0; // Certificate 1 mandatory
        if (company.cert2Type != null && !company.cert2Type.isEmpty()) score += 1.0;

        // Environmental Compliance
        if (company.environmentalCompliance) score += 3.0;

        // Certificate Validity
        LocalDate today = LocalDate.now();
        if (company.cert1Expiry != null) {
            long monthsUntilExpiry = ChronoUnit.MONTHS.between(today, company.cert1Expiry);
            if (monthsUntilExpiry > 12) score += 2.0;
            else if (monthsUntilExpiry >= 6) score += 1.0;
        }
        if (company.cert2Expiry != null) {
            long monthsUntilExpiry = ChronoUnit.MONTHS.between(today, company.cert2Expiry);
            if (monthsUntilExpiry > 12) score += 2.0;
            else if (monthsUntilExpiry >= 6) score += 1.0;
        }

        // Environmental Certification Bonus
        boolean isCert1Environmental = company.cert1Type.toLowerCase().contains("fair trade") ||
                                      company.cert1Type.toLowerCase().contains("organic");
        if (isCert1Environmental) score += 1.0;

        return Math.max(0.0, Math.min(10.0, score));
    }
    
}
// Apply scoring logic
                    double financialRiskRating = calculateFinancialRiskRating(company);
                    double reputationalRiskRating = calculateReputationalRiskRating(company);
                    double complianceRiskRating = calculateComplianceRiskRating(company);
                    double totalScore = financialRiskRating + reputationalRiskRating + complianceRiskRating;
                    String status = totalScore >= 20.0 ? "accepted" : "rejected";

                    // Update database
                    String updateQuery = "UPDATE companies SET financial_risk_rating = ?, reputational_risk_rating = ?, compliance_risk_rating = ?, acceptance_status = ? WHERE company_id = ?";
                    pstmt = conn.prepareStatement(updateQuery);
                    pstmt.setDouble(1, financialRiskRating);
                    pstmt.setDouble(2, reputationalRiskRating);
                    pstmt.setDouble(3, complianceRiskRating);
                    pstmt.setString(4, status);
                    pstmt.setInt(5, Integer.parseInt(company.companyId));
                    pstmt.executeUpdate();