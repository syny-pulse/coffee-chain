package com.recessg_26;

import java.io.File;
import java.io.IOException;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.apache.pdfbox.Loader;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;

public class PDFDataExtractor {

    public static void main(String[] args) {
        String filePath = "C:\\Users\\DELL\\Downloads\\coffee_supply_chain_form (4).pdf"; // Replace with actual path

        // Verify file exists
        File file = new File(filePath);
        if (!file.exists()) {
            System.out.println("Error: File not found at " + filePath);
            return;
        }

        try (PDDocument document = Loader.loadPDF(file)) {
            if (document.isEncrypted()) {
                System.err.println("Error: PDF document is encrypted and cannot be processed.");
                return;
            }

            PDFTextStripper stripper = new PDFTextStripper();
            String text = stripper.getText(document);
            extractFields(text);

        } catch (IOException e) {
            System.err.println("Error processing PDF: " + e.getMessage());
            e.printStackTrace();
        }
    }

    private static void extractFields(String text) {
        // List of fields to extract
        String[][] fields = {
                {"Annual Revenue (UGX):", "Annual Revenue"},
                {"Debt-to-Equity Ratio:", "Debt-to-Equity Ratio"},
                {"Cash Flow Summary - Year 1:", "Cash Flow Year 1"},
                {"Cash Flow Summary - Year 2:", "Cash Flow Year 2"},
                {"Credit Score:", "Credit Score"},
                {"Reference 1 - Name:", "Reference 1 - Name"},
                {"Reference 2 - Name:", "Reference 2 - Name"},
                {"Reference 3 - Name:", "Reference 3 - Name"},
                {"Legal Disputes:", "Legal Disputes"},
                {"Certification 1 - Type:", "Certification 1"},
                {"Certification 2 - Type:", "Certification 2"},
                {"Certification 1 - Expiry:", "Certification 1 Expiry"},
                {"Certification 2 - Expiry:", "Certification 2 Expiry"},
                {"Environmental Compliance:", "Environmental Compliance"}
        };

        for (String[] field : fields) {
            extract(text, field[0], field[1]);
        }
    }

    private static void extract(String text, String label, String fieldName) {
        // Use multiline flag to handle labels across lines
        Pattern pattern = Pattern.compile(Pattern.quote(label) + "\\s*(.*?)(?=\\n\\S|$)", Pattern.DOTALL);
        Matcher matcher = pattern.matcher(text);
        if (matcher.find()) {
            String value = matcher.group(1).trim();
            System.out.println(fieldName + ": " + (value.isEmpty() ? "Not found" : value));
        } else {
            System.out.println(fieldName + ": Not found");
        }
    }
}