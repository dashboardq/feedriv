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
                    <h1>Privacy Policy</h1>

                    <p><b>Contact Information</b></p>
                    <p>This web site is maintained and operated by <b><?php esc(ao()->env('APP_AUTHOR')); ?></b>.</p>
                    <p>The mailing address for <?php esc(ao()->env('APP_AUTHOR')); ?> is<br /><b> <?php echo nl2br(_esc(ao()->env('APP_AUTHOR_ADDRESS'))); ?></b></p>  
                    <p>We can also be reached using our <a href="/contact/">contact form</a>.</p>
                    <p><a name="1"></a><b>Our Commitment To Privacy</b></p>
                    <p>Your privacy is important to us. To better protect your privacy we provide this notice explaining our online information practices and the choices you can make about the way your information is collected and used. To make this notice easy to find, a link can be found at the bottom of each page of the website.</p>
                    <p><a name="2"></a><b>The Information We Collect:</b></p>
                    <p>This notice applies to all information collected or submitted on the <?php esc(ao()->env('APP_NAME')); ?>NumbersQ website. On some pages, you can order products, make requests, and register to receive materials. The types of personal information collected at these pages are: <br />&#8211; contact information<br />&#8211; billing information<br />&#8211; account information</p>
                    <p><a name="3"></a><b>The Way We Use Information:</b></p>
                    <p>We use the information you provide about yourself when placing an order only to complete that order. We do not share this information with outside parties except to the extent necessary to complete that order.</p>
                    <p>We use return email addresses to answer the email we receive. Such addresses are not used for any other purpose and are not shared with outside parties.</p>
                    <p>You can register with our website if you would like to access account information as well as updates on our new products and services. Information you submit on our website will not be used for this purpose unless you fill out the registration form.</p>
                    <p>We use non-identifying and aggregate information to better design our website and to share with advertisers. For example, we may tell an advertiser that X number of individuals visited a certain area on our website, or that Y number of men and Z number of women filled out our registration form, but we would not disclose anything that could be used to identify those individuals.</p>
                    <p>Finally, we never use or share the personally identifiable information provided to us online in ways unrelated to the ones described above without also providing you an opportunity to opt-out or otherwise prohibit such unrelated uses.</p>
                    <p><a name="6"></a><b>How You Can Access Or Correct Your Information</b></p>
                    <p>You can access all your personally identifiable information that we collect online and maintain by contacting us directly using the contact form found on the website. We use this procedure to better safeguard your information.</p>
                    <p>You can correct factual errors in your personally identifiable information by sending us a request that credibly shows error.</p>
                    <p>To protect your privacy and security, we will also take reasonable steps to verify your identity before granting access or making corrections.</p>
                    <p><a name="7"></a><b>Changes To The Privacy Policy</b></p>
                    <p>Changes to the this policy will be posted on this page.</p>
                    <p>This policy was last modified on <?php esc(ao()->env('APP_PRIVACY_UPDATED')); ?>.</p>
                    <p><a name="7"></a><b>How To Contact Us</b></p>
                    <p>Should you have other questions or concerns about these privacy policies, please contact us using our <a href="/contact/">contact form</a>.</p>
                </section>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>
