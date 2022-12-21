<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('header'); ?>
            <main>
                <section class="page">
                    <h1>Terms of Use</h1>
                    <p>Read This Terms of Use Agreement Before Accessing Website.</p>
                    <p>Effective Date: This Terms of Use Agreement was last updated on <?php esc(ao()->env('APP_TERMS_UPDATED')); ?>.</p>
                    <p>The <?php esc(ao()->env('APP_NAME')); ?> website is an online information service provided by <?php esc(ao()->env('APP_AUTHOR')); ?>. This Terms of Use Agreement sets forth the standards of use of the <?php esc(ao()->env('APP_NAME')); ?> Online Service for Registered Members. By using the <?php esc(ao()->env('APP_NAME')); ?> website you (the &#8220;Member&#8221;) agree to these terms and conditions. If you do not agree to the terms and conditions of this agreement, you should immediately cease all usage of this website. We reserve the right, at any time, to modify, alter, or update the terms and conditions of this agreement without prior notice. Modifications shall become effective immediately upon being posted at the <?php esc(ao()->env('APP_NAME')); ?> website. Your continued use of the Service after amendments are posted constitutes an acknowledgement and acceptance of the Agreement and its modifications. Except as provided in this paragraph, this Agreement may not be amended.</p>
                    <p><strong>1. Description of Service</strong></p>
                    <p><?php esc(ao()->env('APP_AUTHOR')); ?> is providing Member with online information service. Member must provide (1) all equipment necessary for their own Internet connection, including computer and modem and (2) provide for Member?s access to the Internet, and (3) pay any fees relate with such connection.</p>
                    <p><strong>2. Disclaimer of Warranties.</strong></p>
                    <p>The site is provided by <?php esc(ao()->env('APP_AUTHOR')); ?> on an &#8220;as is&#8221; and on an &#8220;as available&#8221; basis. To the fullest extent permitted by applicable law, <?php esc(ao()->env('APP_AUTHOR')); ?> makes no representations or warranties of any kind, express or implied, regarding the use or the results of this website in terms of its correctness, accuracy, reliability, or otherwise. <?php esc(ao()->env('APP_AUTHOR')); ?> shall have no liability for any interruptions in the use of this Website. <?php esc(ao()->env('APP_AUTHOR')); ?> disclaims all warranties with regard to the information provided, including the implied warranties of merchantability and fitness for a particular purpose, and non-infringement. Some jurisdictions do not allow the exclusion of implied warranties, therefore the above-referenced exclusion is inapplicable.</p>
                    <p><strong>3. Limitation of Liability</strong></p>
                    <p><?php esc(ao()->env('APP_AUTHOR')); ?> SHALL NOT be liable for any damages whatsoever, and in particular <?php esc(ao()->env('APP_AUTHOR')); ?> shall not be liable for any special, indirect, consequential, or incidental damages, or damages for lost profits, loss of revenue, or loss of use, arising out of or related to this website or the information contained in it, whether such damages arise in contract, negligence, tort, under statute, in equity, at law, or otherwise, even if <?php esc(ao()->env('APP_AUTHOR')); ?> has been advised of the possibility of such damages. SOME JURISDICTIONS DO NOT ALLOW FOR THE LIMITATION OR EXCLUSION OF LIABILITY FOR INCIDENTAL OR CONSEQUENTIAL DAMAGES, THEREFORE SOME OF THE ABOVE LIMITATIONS IS INAPPLICABLE.</p>
                    <p><strong>4. Indemnification</strong></p>
                    <p>Member agrees to indemnify and hold <?php esc(ao()->env('APP_AUTHOR')); ?>, its parents, subsidiaries, affiliates, officers and employees, harmless from any claim or demand, including reasonable attorneys? fees and costs, made by any third party due to or arising out of Member?s use of the Service, the violation of this Agreement, or infringement by Member, or other user of the Service using Member?s computer, of any intellectual property or any other right of any person or entity.</p>
                    <p><strong>5. Members Account</strong></p>
                    <p>All Members of the Service shall receive a password and an account. Members are entirely responsible for any and all activities which occur under their account whether authorized or not authorized. Member agrees to notify <?php esc(ao()->env('APP_AUTHOR')); ?> of any unauthorized use of Member?s account or any other breach of security known or should be known to the Member. Member?s right to use the Service is personal to the Member. Member agrees not to resell or make any commercial use of the Service without the express written consent of <?php esc(ao()->env('APP_AUTHOR')); ?>. Members agree to maintain one account per user.</p>
                    <p><strong>6. Modifications and Interruption to Service</strong></p>
                    <p><?php esc(ao()->env('APP_AUTHOR')); ?> reserves the right to modify or discontinue the Service with or without notice to the Member. <?php esc(ao()->env('APP_AUTHOR')); ?> shall not be liable to Member or any thiy, that those Vendors endorse or have any affiliation with <?php esc(ao()->env('APP_AUTHOR')); ?>.</p>
                    <p><strong>12. Notification of Claimed Copyright Infringement</strong></p>
                    <p>Pursuant to Section 512(c) of the Copyright Revision Act, as enacted through the Digital Millennium Copyright Act, <?php esc(ao()->env('APP_AUTHOR')); ?> designates the following individual as its agent for receipt of notifications of claimed copyright infringement.</p>
                    <p>By Mail:</p>
                    <p><?php echo nl2br(_esc(ao()->env('APP_AUTHOR_ADDRESS'))); ?></p>
                    <p>By Email:<br />
                    <noscript>Please enable JavaScript to see the email.</noscript>
                    <script>document.write('<a href="mailto:' + <?php echo "'" . implode("' + '", str_split(ao()->env('APP_AUTHOR_EMAIL'))) . "'"; ?> + '">' + <?php echo "'" . implode("' + '", str_split(ao()->env('APP_AUTHOR_EMAIL'))) . "'"; ?> + '</a>'); </script></p>
                    <p><strong>13. Botnets</strong></p>
                    <p><?php esc(ao()->env('APP_AUTHOR')); ?> retains the right, at our sole discretion, to terminate any accounts involved with botnets and related activities. If any hostnames are used as command and control points for botnets, <?php esc(ao()->env('APP_AUTHOR')); ?> reserves the right to direct the involved hostnames to a honeypot, loopback address, logging facility, or any other destination at our discretion.</p>
                    <p><strong>14. Other Terms </strong></p>
                    <p>If any provision of this Terms of Use Agreement shall be unlawful, void or unenforceable for any reason, the other provisions (and any partially-enforceable provision) shall not be affected thereby and shall remain valid and enforceable to the maximum possible extent. You agree that this Terms of Use Agreement and any other agreements referenced herein may be assigned by <?php esc(ao()->env('APP_AUTHOR')); ?>, in our sole discretion, to a third party in the event of a merger or acquisition. This Terms of Use Agreement shall apply in addition to, and shall not be superseded by, any other written agreement between us in relation to your participation as a Member. Member agrees that by accepting this Terms of Use Agreement, Member is consenting to the use and disclosure of their personally identifiable information and other practices described in our Privacy Policy Statement.</p>
                </section>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>
