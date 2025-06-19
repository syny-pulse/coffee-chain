package com.recessg_26;

import java.util.Properties;

import jakarta.mail.Authenticator;
import jakarta.mail.Message;
import jakarta.mail.MessagingException;
import jakarta.mail.PasswordAuthentication;
import jakarta.mail.Session;
import jakarta.mail.Transport;
import jakarta.mail.internet.InternetAddress;
import jakarta.mail.internet.MimeMessage;


class Email {

    // General method to send email
    static void sendMail(String recipient, String subject, String messageBody) {
        String username = "groupimak@gmail.com";
        String password = "diwt ojua zrvg bdxi";

        Properties properties = new Properties();
        properties.put("mail.smtp.auth", "true");
        properties.put("mail.smtp.starttls.enable", "true");
        properties.put("mail.smtp.host", "smtp.gmail.com");
        properties.put("mail.smtp.port", "587");


        Session session = Session.getInstance(properties, new Authenticator() {
            @Override
            protected PasswordAuthentication getPasswordAuthentication() {
                return new PasswordAuthentication(username, password);
            }
        });

        try {
            Message message = new MimeMessage(session);
            message.setFrom(new InternetAddress(username));
            message.setRecipients(Message.RecipientType.TO, InternetAddress.parse(recipient));
            message.setSubject(subject);
            message.setText(messageBody);
            

            Transport.send(message);

            System.out.println("Email sent successfully to " + recipient);
        }
        catch (MessagingException e) {
            e.printStackTrace();
            System.out.println("Failed to send email to " + recipient);
        }
    }

    static void rejectEmail(Server.CompanyPdfData company) {
    	 String subject = "Coffee Supply Chain Partnership Application - Update Required";
    String messageBody = String.format(
        "Dear " + company.name + ",\n\n" +
        "I hope this email finds you well.\n\n" +
        "Thank you for your interest in joining our coffee supply chain network. We have carefully reviewed your partnership application and supporting documentation.\n\n" +
        "After thorough evaluation of your financial standing, compliance certifications, and references, we regret to inform you that your application does not currently meet our partnership requirements. This decision is based on our comprehensive risk assessment criteria that ensure the stability and reliability of our supply chain network.\n\n" +
        "We encourage you to:\n" +
        "• Review and strengthen your financial documentation\n" +
        "• Obtain additional industry certifications, particularly environmental compliance certificates\n" +
        "• Enhance your business references and relationships\n" +
        "• Address any outstanding legal or compliance issues\n\n" +
        "We value your commitment to the coffee industry and would welcome the opportunity to reconsider your application once these areas have been addressed. Please feel free to reapply after making the necessary improvements.\n\n" +
        "Should you have any questions about this decision or require clarification on our requirements, please don't hesitate to contact our partnership team.\n\n" +
        "We appreciate your understanding and wish you success in your business endeavors.\n\n" +
        "Best regards,\n\n" +
        "Partnership Review Team\n" +
        "Coffee Supply Chain Network\n" +
        "Email: partnerships@coffeesupplychain.com\n" +
        "Phone: +256 771 763 352"
    );
    	sendMail(company.email, subject, messageBody);
    }
    
    static void visitScheduledEmail(Server.CompanyPdfData company) {
    	String subject = "Coffee Supply Chain Partnership - Site Visit Scheduled";
    String messageBody = String.format(
        "Dear " + company.name + ",\n\n" +
        "I hope this email finds you well.\n\n" +
        "Congratulations! We are pleased to inform you that your coffee supply chain partnership application has successfully passed our initial evaluation process.\n\n" +
        "Your application demonstrated strong financial stability, excellent compliance standards, and impressive business references. Based on our comprehensive risk assessment, we are excited to move forward with the next phase of our partnership process.\n\n" +
        "Next Steps:\n" +
        "We would like to schedule a site visit to your facilities to:\n" +
        "• Conduct a physical assessment of your operations\n" +
        "• Meet and discuss partnership terms\n" +
        "• Review your quality control processes\n" +
        "• Finalize compliance and certification verification\n\n" +
        "Our partnership team will contact you within the next 3-5 business days to coordinate a mutually convenient time for the site visit. Please ensure that key personnel and relevant documentation are available during this visit.\n\n" +
        "What to Prepare:\n" +
        "• Original copies of all certifications mentioned in your application\n" +
        "• Financial statements and supporting documents\n" +
        "• Quality control and processing documentation\n" +
        "• Safety and environmental compliance records\n\n" +
        "We are impressed with your commitment to quality and sustainability in the coffee industry. This site visit represents an important step toward establishing a mutually beneficial long-term partnership.\n\n" +
        "If you have any questions or need to discuss any specific requirements before our visit, please feel free to contact our partnership coordination team.\n\n" +
        "We look forward to meeting you and exploring this exciting partnership opportunity.\n\n" +
        "Best regards,\n\n" +
        "Partnership Development Team\n" +
        "Coffee Supply Chain Network\n" +
        "Email: partnerships@coffeesupplychain.com\n" +
        "Phone: +256 771 763 352\n\n" +
        "P.S. Please confirm receipt of this email and your availability for the upcoming site visit coordination call."
        
    );

    sendMail(company.email, subject, messageBody);
}

}

	

