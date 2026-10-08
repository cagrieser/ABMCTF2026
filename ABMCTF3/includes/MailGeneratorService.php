<?php

class MailGeneratorService {
    public static function getCounts() {
        $login_time = $_SESSION['login_time'] ?? time();
        $current_time = time();
        
        $inbox = self::getInboxMails($login_time, $current_time);
        $sent = self::getSentMails($login_time, $current_time);
        $spam = self::getSpamMails($login_time, $current_time);
        $trash = self::getTrashMails($login_time, $current_time);

        $inbox_unread = count(array_filter($inbox, fn($m) => !$m['is_read']));
        $spam_unread = count(array_filter($spam, fn($m) => !$m['is_read']));

        return [
            'inbox' => ['total' => count($inbox), 'unread' => $inbox_unread],
            'sent' => ['total' => count($sent), 'unread' => 0],
            'spam' => ['total' => count($spam), 'unread' => $spam_unread],
            'trash' => ['total' => count($trash), 'unread' => 0],
        ];
    }

    public static function generate($folder = 'inbox') {
        $login_time = $_SESSION['login_time'] ?? time();
        $current_time = time();
        
        $mails = [];

        if ($folder === 'inbox') {
            $mails = self::getInboxMails($login_time, $current_time);
        } elseif ($folder === 'sent') {
            $mails = self::getSentMails($login_time, $current_time);
        } elseif ($folder === 'spam') {
            $mails = self::getSpamMails($login_time, $current_time);
        } elseif ($folder === 'trash') {
            $mails = self::getTrashMails($login_time, $current_time);
        }

        // Sort by timestamp descending
        usort($mails, function($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });

        return $mails;
    }

    private static function isRead($id, $is_new) {
        if (isset($_SESSION['read_mails']) && in_array($id, $_SESSION['read_mails'])) {
            return true;
        }
        return !$is_new;
    }

    private static function getInboxMails($login_time, $current_time) {
        $mails = [];

        // Base historical emails (Increased to 50)
        for ($i = 0; $i < 50; $i++) {
            $id = self::uuidv4FromSeed("inbox_hist_$i");
            $mail = self::generateRandomCorporateMail($login_time - (($i + 1) * 3600), "inbox_hist_$i");
            $mail['is_read'] = self::isRead($id, false);
            $mails[] = $mail;
        }

        // Calculate new emails (1 email every 30 seconds)
        $elapsed = $current_time - $login_time;
        $new_email_count = floor($elapsed / 30);
        
        for ($i = 0; $i < $new_email_count; $i++) {
            if ($i === 0) {
                // The Ransomware Flag Email arrives at 30 seconds!
                $mails[] = [
                    'id' => 'f3c12a6d-8f91-4b9c-96c3-4e98ddc8ba1f',
                    'sender' => 'lockbit-support@darkweb-ransom.onion',
                    'to' => 'all-staff@flightcore.com',
                    'cc' => '',
                    'bcc' => '',
                    'subject' => '[QUARANTINED] URGENT: Ransomware Attack Attempt Detected',
                    'timestamp' => date('Y-m-d H:i:s', $login_time + 30),
                    'is_recalled' => true,
                    'severity' => 'CRITICAL',
                    'is_read' => self::isRead('f3c12a6d-8f91-4b9c-96c3-4e98ddc8ba1f', true),
                    'preview' => 'RECALLED BY SECURITY INCIDENT RESPONSE TEAM (SIRT)'
                ];
            } else {
                $mail_time = $login_time + ($i * 30) + 1;
                $id = self::uuidv4FromSeed("inbox_new_$i");
                $mail = self::generateRandomCorporateMail($mail_time, "inbox_new_$i");
                $mail['is_read'] = self::isRead($id, true);
                $mails[] = $mail;
            }
        }

        return $mails;
    }

    private static function getSentMails($login_time, $current_time) {
        $mails = [];
        for ($i = 0; $i < 30; $i++) {
            $id = self::uuidv4FromSeed("sent_hist_$i");
            $mail = self::generateRandomCorporateMail($login_time - (($i + 1) * 7200), "sent_hist_$i");
            $mail['to'] = $mail['sender'];
            $mail['sender'] = 'bilge.demir@flightcore.com';
            $mail['is_read'] = true; // Sent items are always read
            $mails[] = $mail;
        }
        return $mails;
    }

    private static function getSpamMails($login_time, $current_time) {
        $mails = [];
        $elapsed = $current_time - $login_time;
        $new_email_count = floor($elapsed / 30); // 1 spam every 30 seconds

        for ($i = 0; $i < 30; $i++) {
            $id = self::uuidv4FromSeed("spam_hist_$i");
            $mail = self::generateRandomSpamMail($login_time - (($i + 1) * 86400), "spam_hist_$i");
            $mail['is_read'] = self::isRead($id, false);
            $mails[] = $mail;
        }
        for ($i = 0; $i < $new_email_count; $i++) {
            $mail_time = $login_time + ($i * 30) + 2;
            $id = self::uuidv4FromSeed("spam_new_$i");
            $mail = self::generateRandomSpamMail($mail_time, "spam_new_$i");
            $mail['is_read'] = self::isRead($id, true);
            $mails[] = $mail;
        }
        return $mails;
    }

    private static function getTrashMails($login_time, $current_time) {
        $mails = [];
        for ($i = 0; $i < 40; $i++) {
            $id = self::uuidv4FromSeed("trash_hist_$i");
            $mail = self::generateRandomCorporateMail($login_time - (($i + 1) * 14400), "trash_hist_$i");
            $mail['is_read'] = true;
            $mails[] = $mail;
        }
        return $mails;
    }

    private static function generateRandomCorporateMail($time, $seed_string) {
        $senders = ['hr@flightcore.com', 'it-support@flightcore.com', 'sarah.jenkins@flightcore.com', 'alex.wright@flightcore.com', 'jira-noreply@flightcore.com', 'security@flightcore.com', 'vendor-updates@boeing.com', 'payroll@flightcore.com', 'monitoring@flightcore.com', 'legal@flightcore.com'];
        $subjects = [
            'Build #4092 Passed and Ready for Review', 'Alert: High CPU Usage on DB-01 Detected', 'Meeting Notes: Radar Calibration and Maintenance',
            'Pending Merge Request: Navigation Core System v2', 'Reminder: Complete Mandatory Cybersecurity Training',
            'Weekly Standup Update and KPI Review', 'Vendor API Maintenance Window (Action Required)', 'Flight Log Analysis: TX-902 Anomaly Detection',
            'Action Required: Renew Enterprise SSL Certificates', 'Welcome to the new MailSuite v2.4 Enterprise Edition',
            'Q3 Technical Performance Review Schedule and Guidelines', 'Upcoming Server Rack Migration to AWS US-East',
            'Re: Nav Systems Update and Simulator Test Results', 'Expense Report #4029 Approved - International Travel'
        ];
        $bodies = [
            "Please review the attached logs when you have a moment. We have noticed several discrepancies in the recent server load patterns, particularly around peak hours on the US-East region. Our preliminary analysis suggests a memory leak in the core navigation module, but we need your expert eyes to confirm this.\n\nLet me know your availability for a deep-dive session this Thursday. We need to sign this off before Friday to meet the deployment window.",
            "Automated notification from FlightCore Monitoring Service.\n\nThe CPU threshold on database node DB-01 has exceeded 85% for the last 15 minutes. This usually happens during the nightly batch processing, but the schedule shows no active cron jobs. Please investigate the active queries and kill any hanging processes if necessary. No immediate action required unless the threshold is exceeded for another 10 minutes.",
            "I've updated the Confluence page with the latest architecture diagrams and API definitions. We have successfully mitigated the latency issues observed during the last radar calibration run. However, the telemetry parsing engine still requires some fine-tuning to handle the increased throughput from the new Boeing 737 Max sensor arrays.\n\nPlease let me know if you have any feedback or if we need to schedule a follow-up meeting with the hardware vendors.",
            "Can we discuss this over a quick call tomorrow? The implementation of the new cryptographic handshake in the authentication layer seems a bit complex, and I want to make sure we are not introducing any breaking changes for the legacy mobile clients.\n\nI have left a few comments on the PR regarding the token validation logic. Let's sync up after the daily standup.",
            "This is a friendly reminder to submit your weekly timesheets and expense reports before the end of the week. The accounting department will be closing the ledger early this month due to the upcoming holidays.\n\nAlso, ensure that your project codes are accurately mapped to the new R&D cost centers. Any discrepancies will delay your reimbursement.",
            "The staging environment is currently down for unscheduled maintenance. Our DevOps team is working on rolling back a faulty database migration that caused a data corruption issue in the user session tables. Please hold off on any deployments or automated testing until further notice.\n\nWe expect the environment to be fully operational within the next 3-4 hours. We apologize for the inconvenience.",
            "Great work on the recent security patch! The telemetry data looks much cleaner now, and the incident response team has reported a 40% drop in false positive alerts from the WAF. The board has acknowledged your team's effort in this quarter's all-hands meeting.\n\nKeep up the excellent work, and let's make sure we document these new mitigation strategies in the internal wiki.",
            "Dear Bilge,\n\nPlease find your upcoming Q3 performance review schedule attached in the internal portal. Ensure that you have completed your self-assessment by this Friday. We will be focusing on your contributions to the MailSuite v2.4 rollout and the successful mitigation of the SSRF vulnerability reported by the external auditors.\n\nBest Regards,\nHR Operations",
            "Hi Team,\n\nWe will be migrating the primary backup servers this weekend (Saturday 02:00 AM EST). Expect intermittent downtime on the legacy authentication gateway during this maintenance window. All critical microservices will be automatically failed over to the disaster recovery site, but internal administrative tools might experience temporary outages.\n\nRegards,\nIT Infrastructure Support",
            "Bilge,\n\nThe 737 navigation patch you pushed yesterday was successfully tested in the simulator. Everything looks solid. The altimeter drift issue has been completely resolved. It's ready for your final sign-off before we push the firmware update to the staging fleet.\n\nThanks,\nSarah"
        ];

        $hash = md5($seed_string);
        $sender = $senders[hexdec(substr($hash, 0, 4)) % count($senders)];
        $subject = $subjects[hexdec(substr($hash, 4, 4)) % count($subjects)];
        $body = $bodies[hexdec(substr($hash, 8, 4)) % count($bodies)];
        
        $has_cc = (hexdec(substr($hash, 12, 1)) % 3) === 0;
        $cc_address = $has_cc ? 'engineering-team@flightcore.com' : '';

        // Randomize recalled state and severity
        $is_recalled = (hexdec(substr($hash, 13, 2)) % 100) < 15; // 15% chance to be recalled
        if ($is_recalled) {
            $subject = '[RECALLED] ' . $subject;
            $body = 'RECALLED BY ADMINISTRATIVE ACTION';
        }

        $severities = ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'];
        $severity = $severities[hexdec(substr($hash, 15, 1)) % count($severities)];

        return [
            'id' => self::uuidv4FromSeed($seed_string),
            'sender' => $sender,
            'to' => 'bilge.demir@flightcore.com',
            'cc' => $cc_address,
            'bcc' => '',
            'subject' => $subject,
            'timestamp' => date('Y-m-d H:i:s', $time),
            'is_recalled' => $is_recalled,
            'severity' => $severity,
            'preview' => $body
        ];
    }

    private static function generateRandomSpamMail($time, $seed_string) {
        $senders = ['offers@cheapflights-now.net', 'admin-update@fligthcore.com', 'crypto-invest@scam-alert.io', 'newsletter@aviation-weekly.com', 'dr.mbeke@international-funds.org', 'lottery@euro-millions-winner.com'];
        $subjects = ['WIN A FREE FLIGHT TO BAHAMAS - EXCLUSIVE OFFER', 'URGENT: Corporate Password Expiry Notification', 'Urgent Action Required: Your BTC Wallet requires verification', 'Top 10 Turboprop Engines of 2026 - Aviation Weekly', 'URGENT: Confidential Business Proposal / Investment', 'Congratulations! You have been selected!'];
        $bodies = [
            "CONGRATULATIONS!\n\nYour corporate email bilge.demir@flightcore.com has been selected for our yearly FlightCore partner giveaway! Click the secure link below to claim your free business-class tickets to the Bahamas!\n\nThis offer is valid for the next 24 hours only. Do not miss out on this amazing opportunity!\n\n[UNSAFE LINK REMOVED BY MAILSUITE VIRUS SCANNER]",
            "Dear Employee,\n\nYour FlightCore enterprise password expires in exactly 2 hours. According to the new IT security policy, if you do not update it immediately, you will lose access to the MailSuite, JIRA, and the internal VPN.\n\nPlease click here to retain your access and verify your credentials: http://fligthcore-auth-update-secure.com/login\n\nFailure to comply will result in an automatic account lockout.\n\nIT Security Dept.",
            "Dear User,\n\nWe noticed unusual activity on your cryptocurrency wallet originating from a foreign IP address. To ensure the safety of your assets, your account has been temporarily restricted.\n\nPlease verify your 12-word seed phrase immediately to prevent permanent account suspension and loss of funds. Click the button below to start the secure recovery process.\n\nRegards,\nThe Crypto Security Team",
            "In this week's issue of Aviation Weekly, we dive deep into the new hybrid turboprop technologies shaping regional flights. Discover how industry leaders are leveraging AI to optimize fuel efficiency and reduce carbon emissions by up to 30%.\n\nRead the full report online and join the discussion in our community forums.\n\nTo stop receiving these emails, click Unsubscribe here.",
            "Dear Sir/Madam,\n\nI am Dr. Mbeke, managing director of the International Aviation Fund. I have a highly lucrative and risk-free investment opportunity involving stranded aviation assets worth $14,000,000.\n\nI need a reliable foreign partner to help process the transfer of these funds. You will be compensated with 25% of the total amount. Please reply immediately with your banking details to proceed.",
            "You are the lucky winner of our random email draw! Your address was selected from a global database of IT professionals. You have won a brand new MacBook Pro M4 Max!\n\nPlease fill out the attached form and pay the small shipping fee of $49.99 to claim your prize."
        ];

        $hash = md5($seed_string);
        $sender = $senders[hexdec(substr($hash, 0, 4)) % count($senders)];
        $subject = $subjects[hexdec(substr($hash, 4, 4)) % count($subjects)];
        $body = $bodies[hexdec(substr($hash, 8, 4)) % count($bodies)];

        // Randomize recalled state for spam as well (maybe WAF blocked it?)
        $is_recalled = (hexdec(substr($hash, 13, 2)) % 100) < 10; // 10% chance
        if ($is_recalled) {
            $subject = '[QUARANTINED] ' . $subject;
            $body = 'RECALLED BY MAILSUITE WAF FILTER';
        }

        return [
            'id' => self::uuidv4FromSeed($seed_string),
            'sender' => $sender,
            'to' => 'bilge.demir@flightcore.com',
            'cc' => '',
            'bcc' => 'undisclosed-recipients@null.com',
            'subject' => $subject,
            'timestamp' => date('Y-m-d H:i:s', $time),
            'is_recalled' => $is_recalled,
            'severity' => 'LOW', // spam is low severity routing
            'preview' => $body
        ];
    }

    private static function uuidv4FromSeed($seed) {
        $hash = md5($seed);
        return sprintf('%08s-%04s-%04x-%04x-%12s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x4000,
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
            substr($hash, 20, 12)
        );
    }
}
