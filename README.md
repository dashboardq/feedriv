# FeedRiv

An open source, MIT licensed feed reader built with a custom PHP MVC backend framework and [TACE](https://www.agraddy.com/introducing-tace) for front end development. It focuses on giving you the tools you need to filter RSS feeds and tag feed items. If you would like to use the hosted version, it is available at [FeedRiv - Feed Reader](https://www.feedriv.com).

---

## Other DashboardQ Projects
1\. [MetricRiv](https://www.metricriv.com/)  
Track your business and marketing metrics: [Launch Post](https://www.agraddy.com/startup-1-numbersq-know-the-state-of-your-business-in-30-seconds) | [Open Source Code](https://github.com/dashboardq/metricriv)  

2\. [TwitRiv](https://twitriv.com/)  
(*Currently not usable due to Twitter API changes.*) Take control of your Twitter experience : [Launch Post](https://www.agraddy.com/startup-2-twitriv-control-your-twitter-experience) | [Open Source Code](https://github.com/dashboardq/twitriv)

3\. [FeedRiv](https://www.feedriv.com/)  
A simple RSS feed reader that lets you tag and rate items. [Launch Post](https://www.agraddy.com/startup-3-feedriv-simple-rss-feed-reader) | [Open Source Code](https://github.com/dashboardq/feedriv)  

---

## Who
FeedRiv is created by [Anthony Graddy](https://www.agraddy.com) as a part of the [12 Startups in 12 Months (Open Source Edition) challenge](https://www.agraddy.com/12-startups-in-12-months-open-source-edition). This is the 3rd startup of the challenge.

## What
FeedRiv is a simple RSS feed reader that lets you tag and rate items. I use RSS feed readers to track potential open source jobs so I needed a feed reader that let me tag items that I needed to respond to and would also allow me to rate items so that I could quickly find the jobs that match my skillset.

## When
FeedRiv is available now but it has been recently released and should be considered an early beta version. If you would like to request additional features, please open a feature request or if this is a private request, you can submit a new ticket in the DashboardQ Support system. Note that paying customers will receive higher priority.

## Where
FeedRiv is open source and can be installed on any server that supports modern PHP frameworks. The PHP used is very basic and should work with web hosts that support PHP 7.3 and above. It will probably work with older versions of PHP, but I'm not testing or guaranteeing anything for older versions of PHP.

## How

I prefer documentation that has at least one clear step-by-step guide of how to get things working. I come across so much documentation that leave out valuable/important steps. My goal is that the documentation is so simple, a 10 year old can install it following the steps. If you feel anything is lacking, please let me know (you can contact me anywhere I have an account - if for some reason I don't respond, try a different platform where I have an account).

I've provided two installation versions below: Experienced Developer and No Experience. Please pick the one that is right for you (or combine the insights of the two).

### Experienced Developer

These instructions will install FeedRiv on your local computer. The instructions are based on a Unix command line installation but should be easy to apply to other systems.

Required tools:
* PHP
* MySQL
* Git
* Composer

```
# Get started (put it anywhere on your system, I'm putting it in the home directory)
cd ~

# Clone the repo
git clone https://github.com/dashboardq/feedriv.git feedriv
cd feedriv

# Set up MySQL database
mysql -u root -p --vertical
mysql> CREATE DATABASE feedriv;
mysql> exit;

# Set up the environment config values (enter the appropriate MySQL data)
# If you are developing, use "dev" for the "APP_ENV" environment variable, 
# if you are putting this on a publicly available website, use the "prod" value.
cp .example.env.php .env.php

# Edit the .env.php file.
# Make sure you update: APP_HOST, APP_SITE, DB_NAME, DB_USER, and DB_PASS
vim .env.php

# If you are using Apache, edit the .htaccess file and update the domain name.
vim public/.htaccess

# Run the migrations
php ao mig init
php ao mig up

# Install dependencies
composer install

# (optional) Add a cron that runs every minute
crontab -e
* * * * * cd /home/YOUR_USERNAME/feedriv && php ao refresh > /dev/null 2>&1

# (optional) Or you can manually refresh the feeds if you are just testing the system out.
php ao refresh

# Serve the site locally
cd public
php -S localhost:8080

# Visit the site in your browser:
# http://localhost:8080

# You should be seeing the FeedRiv home page when you visit it in your browser. 
# On the login page, you should be able to register an account.
```

### No Experience

I'm going to be listing out steps on how to install FeedRiv using [Cloudways](https://www.cloudways.com/en/?id=1100140) so I guess that 10 year old is going to need to find a credit card (the Cloudways link is an affiliate link). The reason I am using Cloudways as an example is because they have a simple, easy to use platform that makes setting up an affordable VPS very simple. Unfortunately, they only support PHP so for any other hosting needs, you will need to use a different host.

I'm going to start off with some introduction text written for intermediate users. Don't worry if the introduction doesn't make sense to you. The most important part to understand is step 1. If the steps themselves don't make sense, please let me know.

#### Let's Get Started

FeedRiv code is written using a custom PHP framework. The framework is in an early alpha status (there are currently no tests and this is basically the first draft you are viewing - eventually the goal is to separate out the framework as a separate system). The framework has a similar structure to [Laravel](https://github.com/laravel/laravel), meaning it assumes the majority of the files are not in the public directory and accessible from the web. If your web host supports Laravel, then it should be able to host FeedRiv.

Note that the setup instructions use SFTP. If you are not familiar with SFTP, it stands for "Secure File Transfer Protocol." It is an agreed upon standard on how to move files between computers. There are numerous free and paid SFTP apps available; I'll be mentioning some free ones below. 

We are going to be uploading files from our computer to our server using SFTP. Note that this is an old school way of setting up a site. If you are a 10 year old, you are probably going to get bullied by the cool kids who have moved on to more "sophisticated" methods of "app deployment."

The main points are in bold. The other text is additional context if the bold text doesn't make sense. If you are skimming, just look at the bold points.

1\. First we are going to get our tools installed. **Install a text editor.**
  * This step is going to depend on what operating system you are using. 
  * If you are using **Linux**, you probably already have a text editor and know which one you prefer. 
    * If you don't have a text editor preference, maybe try **Gedit** (it may already be installed).
  * If you are using a **Mac**, I would recommend installing **[CotEditor](https://coteditor.com/)** - I haven't used it myself so don't put a lot of weight into this this recommendation.
  * If you are using **Windows**, I would recommend installing **[Notepad++](https://notepad-plus-plus.org/downloads/)** - choose the latest version (don't worry about the names of each one).
  * If you want to really dive in deep, **learn [Vim](https://www.vim.org/)**. It is a text editor available on every operating system for free. It has a very steep learning curve, but once you understand it, you will never want to use anything else.
  * If anybody tells you to learn **Emacs, don't do it**, they are trying to play a prank on you. (yes, this line is a joke, but it was written with Vim) (Vim is not responsible for this lame joke and will not affect your humor) (now I'm going to have to explain the joke because if you are 10 years old, you won't understand - Vim and Emacs have been around for decades. Both editors have built up a loyal following, so much so that when one or the other is brought up online, it often results in people spending hours of their life arguing about which one is better, it also causes them to spend way too much time writing about this in README files, but thankfully it can be written quickly if you use Vim).
    * Vim is Charityware (meaning it is free but the creator asks that you donate to a charity they are associated with so if you can - **go donate money to the [International Child Care Fund charity](https://iccf-holland.org/)**).

2\. **Install an SFTP program.**
  * For years I used to enthusiastically recommended that people should **install [FileZilla Client](https://filezilla-project.org/download.php?type=client)** if they needed an SFTP program but I am no longer enthusiastic about the recommendation. It tries to install additional software if you are not paying attention during installation. **Do not install additional software, only install the basic client.**
    * I highly recommend that you **read this article first** to make sure you don't end up with additional junk on your system: [How to Safely Download and Cleanly Install FileZilla FTP Software (with no additional junk)](https://medium.com/web-design-web-developer-magazine/how-to-safely-download-and-cleanly-install-filezilla-ftp-software-with-no-additional-junk-10b27a2d270d).
    * Follow the steps in the article above to install FileZilla. Once you have it installed, there shouldn't be any other issues with the "additional junk."
  * If my warnings above make you wary of using FileZilla, here are some possible alternatives you can check out:
    * [Cyberduck](https://cyberduck.io/) - Available on Linux, Mac, and Windows. Can connect to more services but may not be as easy to use.
    * [WinSCP](https://winscp.net/eng/index.php) - Available on Windows. I have not used it, but it seems to be very similar to FileZilla.
  * I'm not aware of a solid, cross platform open source SFTP progam that I feel comfortable recommending. If you know of one I'm missing, let me know.

3\. **Create a [Cloudways account](https://www.cloudways.com/en/?id=1100140).** 
  * Go to the pricing page. 
  * Make sure "DigitalOcean is selected (DigitalOcean is the default).
  * Change the Slider from "Premium" to "Standard" (Premium is the default)
  * Pick the cheapest plan, it should say "$10" (at least it does at the time of writing this document). It has the option to "Start Free."
  * It will take you to a sign up page. If you need help figuring out the sign up page, let me know.

4\. **Set up the server.**
  * When setting up the server, Cloudways is going to ask what application you want to install. **Choose "Laravel" for the app** (at the time of this writing, the option was Laravel 8.26.1 which will change in the future). We are not going to use Laravel, but FeedRiv has a similar application structure.
  * It is going to ask you for some additional information for your application. Feel free to use whatever you want, but I would recommend that you **use the info below to set up the app**:
    * Name Your Managed App: FeedRiv
    * Name Your Managed Server: FeedRiv
    * Name Your Project: FeedRiv
  * It will ask you for the location of the server. I would recommend that you **pick a location near you for the server location**.
  * Once you've entered everything, **press "Launch Server."**
  * It may take a little time to set up, so go outside and run around or something.

5\. **Understand the server details.**
  * Once the server and application are set up, you should be able to **click on the "www" icon** to open up the details about the Laravel application.
  * You should now be on a page where you can **look at the "Application Management" section** and be on the "Access Details" page.
  * You will see a default URL that Cloudways provides you. Setting up a custom domain is outside the scope of the installation instructions, but it is very easy - please refer to Cloudways documentation if you want to use a custom domain: [How Do I Take My Website Live from Cloudways?](https://support.cloudways.com/en/articles/4805075-how-do-i-take-my-website-live-from-cloudways)
  * When you **click on the Application URL**, it should be a web page that says welcome to Laravel.

6\. **Gather server information for access and your app settings**
  * For each of the items below, save this information for later use. You can save it to a text file or just write it down on a piece of paper. Some of the information should be private, so make sure you don't share it with others.
  * **Find the URL** for your app. This will be located at the following location in your Cloudways admin panel: Applications > [App Name You Created] > Access Details: APPLICATION URL
  * On that same page, you want to save the following items (clicking on them will copy them to your clipboard):
    * **DB Name**
    * **Username**
    * **Password**
  * Get the Master credentials for your server. You can find them at the following location in your Cloudways admin panel: Server > [Server Name You Created] > Master Credentials
  * On the Master Credentials page, save the following items (clicking on them will copy them to your clipboard):
    * **Public IP**
    * **Username**
    * **Password**

7\. **Download the source code for FeedRiv**
  * Go to this link, it should automatically start the download process: [FeedRiv Zip](https://github.com/dashboardq/feedriv/archive/refs/heads/main.zip).

8\. **Unzip the feedriv-main.zip file you just downloaded**
  * If you are not sure how to unzip a file, try right clicking the file and look for an option to unzip. If that is not available, try searching for instructions to unzip a file online. Different computer operating systems have different ways to unzip a file.

9\. **Create and open the setting file**
  * Usually you would edit this file on the server itself, but I want to make this guide as simple as possible.
  * Copy the `.example.env.php` file to `.env.php` 
  * Open the `.env.php` file using your text editor.
  * Note that the file is considered a "hidden file" because it starts with a period. If you cannot see the example files or the new `.env.php` file, you may need to update your directory viewer settings to show hidden files.

10\. **Modify the values in the .env.php file**
  * Using the information you saved in step 6, modify the file as needed:
    * `APP_ENV` - this should be set to `prod` if strangers will have access. If you are just testing on your own, you can leave this set to `dev` to see any errors.
    * `APP_HOST` - this should be the domain name without the "http" prefix. If you are going to use the Cloudways default URL, it may be something like: `phplaravel-123456-7654321.cloudwaysapps.com`
      * If you are using a custom domain, then it may be something like `www.example.com`.
    * `APP_SITE` - this is like the host but it includes the "http" prefix. It does not end with a slash. So it might look like: `http://phplaravel-123456-7654321.cloudwaysapps.com` or `https://www.example.com`
    * `APP_AUTHOR` - the author values are used on the Terms and Privacy pages.
    * `DB_USE`, `DB_INSTALL` - This ensures that the database connection is used and install ensures that the initial database migrations are ran. Leave these both set to `true`. If you are mixing steps from the "Experienced Developer" instructions and using the command line, you will want install set to `false`.
    * `DB_NAME`, `DB_USER`, `DB_PASS` - Each of the db values should match the DB values you saved in step 6.
    * `EMAIL_ADMIN`, `EMAIL_FROM` - If you want to receive email notification (`EMAIL_ADMIN`) or send emails (`EMAIL_FROM`), then these values should be set to your email for admin notifications and the email address that should be used to send emails.
      * In order to send emails using your Cloudways server, you need to set up a 3rd party email sender. You can learn more at the [Cloudways Custom SMTP Instructions](https://support.cloudways.com/en/articles/5130857-how-to-activate-the-custom-smtp-add-on) documentation.
    * `GITHUB_CLIENT_ID`, `GITHUB_CLIENT_SECRET`, `GITHUB_USER_AGENT` - These are optional and only needed if you are planning to track Github numbers.
    * `SCREENSHOTONE_API_KEY` - This is optional and only needed if you are tracking IndieHacker numbers.

11\. **Modify the domain listed in the public/.htaccess file**
  * Using your file editor, open the public/.htaccess file.
  * Find where it says `feedriv.com` and `www.feedriv.com` and change that to the domain that you are using.

12\. **Connect to Cloudways using SFTP.**
  * Using your SFTP program and the Cloudways master credentials you saved in step 6, you want to connect to your Cloudways server.
  * If you are using FileZilla, Cloudways has some instructions on how to connect to the server located here: [Guide to Connecting to Your Application Using SSH/SFTP](https://support.cloudways.com/en/articles/5119485-guide-to-connecting-to-your-application-using-ssh-sftp#h_274b4a0b69)
    * The link should take you directly to the FileZilla section. You can ignore any of the details about SSH and Putty.
  * Once you are logged in with your master credentials, you will need to navigate to the application directory. If you are using FileZilla, this means on the right side, you want to go to: applications > [Your Application Code Name - this usually matches your DB Name] > public_html
  * Once you are in the public_html directory, you should see the default Laravel code and directories.

13\. **Using SFTP, delete any of the default Laravel code that was generated by Cloudways.**
  * You should be in the public_html directory, and there should be files and directories with names like: `README.md`, `app`, `artisan`, `bootstrap`, `composer.json`, `composer.lock`, `config`, `database`, `package.json`, `phpunit.xml`, `public`, `resources`, `routes`, `server.php`, `storage`, `tests`, `vendor`, `webpack.mix.js`
  * **Except for `public`, select each of the items and delete them** (if you can - some may not delete due to permissions). You delete by right clicking a file or directory and selecting "Delete" from the menu.

14\. **Using SFTP, upload FeedRiv to your Cloudways server.**
  * You should be in the public_html directory.
  * **Make sure you can see hidden files**, if you are using FileZilla turn on this option: Server > Force showing hidden files
  * If your SFTP program has a left side and right side pane (using other types of SFTP programs will be slightly different):
    * On the left side, navigate to the directory where you unzipped the feedriv-main.zip file containing the FeedRiv code including the `.env.php` file you edited in step 10.
    * On the right side, you should be in the Cloudways public_html directory.
  * Upload all the files from the left side to the right side by selecting them all and dragging them to the right side.

15\. **Using SSH, install composer and install the dependencies.**
  * You will want to follow the [Composer Cloudway Instructions](https://support.cloudways.com/en/articles/5708669-how-to-install-and-use-composer) to install composer on your server.
  * To install dependecies you will need to **run `composer install` in the main directory**.

16\. **Open the URL with your browser** that Cloudways gave you in the access details section of the Cloudways portal or if you are using a custom domain, use that.
  * When you first open the page, FeedRiv needs to set up the database tables. If you have `DB_INSTALL` set to `true` from step 10, then the first time you load the page, the database tables will be set up.
  * After setting up the database tables, it should then show you the home page of FeedRiv.

17\. **Set up the cron job**
  * If you are not familiar with cron, it is basically a way to run a task at certain intervals on the server. We want the system to check for new RSS items at regular intervals so we need to set up cron.
  * When you are on the "Access Details" page in the Cloudways admin panel mentioned above, look for the left side navigation link for "Cron Job Management" - **Go to the Cron Job Management page**. 
  * **Go to the ADVANCED tab**.
  * In the text field for Advanced, **enter:** `* * * * * cd public_html && php ao refresh > /dev/null 2>&1`
  * **Click the SAVE CHANGES button**.

18\. **Create an account on the login page** 
  * Visit the URL again in your browser for the new FeedRiv site.
  * Go to the login page and register a new user. 
  * Congratulations! You should now have FeedRiv fully installed on your server. [FeedRiv.com](https://www.feedriv.com) has a generous free plan so you can compare the functionality of the free plan with the functionality on the installation on your Cloudways server.


## FAQ

### How do I contribute?

FeedRiv is open source software but it is not currently open contribution software. I'm following the [SQLite model](https://www.sqlite.org/copyright.html) where basically I'm the only one that will be making updates to the code. Feel free to fork, make any changes you want, and use it however you want. If you create a hard fork (meaning you are wanting to take the project in a different direction), I would ask that you rename your project to something else and keep the accreditation in the license file like this:

Right now it says:
```
Copyright (c) 2022 LocationQ
```

If you create a hard fork, please update the license file to something like this:
```
Original Copyright (c) 2022 LocationQ
Copyright (c) 2024 YOUR NAME HERE
```

For an example reference, see how WinterCMS forked OctoberCMS: [WinterCMS License](https://github.com/wintercms/winter/blob/cfa763b714367026f4deef5645c9e64d2f5385bc/LICENSE)

Please don't be offended if I close any pull requests. I would prefer to not receive any pull requests.

### How often is FeedRiv open source code updated?

Updates to the production hosted code are not immediately pushed to the open source version. I have not figured out the specific update schedule that I'm planning to use. I believe [Plausible Analytics](https://github.com/plausible/analytics), which has been a major source of inspiration for this project, uses a six month release schedule. That is one release schedule I have considered, but I haven't definitively decided on anything. I plan to push updates for any significant bugs as soon as possible.  


### Where is the central login and billing code that you are using on FeedRiv and DashboardQ?

If you use the hosted version of FeedRiv, it uses a centralized login and billing system. At this time, I do not have plans to release that code as open source. There are a few other minor differences between the hosted version and the open source version.

All of these additions are added using the web framework's plugin system. I have a DashboardQ plugin that adds all the additional functionality. The plugin system works with the framework's hook system. There are numerous hooks that allow you to intercept the code and perform additional actions. This hook system has taken a lot of inspiration from WordPress hooks. If you look through the code, any place you see `hook('example_action')` is a place that you can intercept and add or modify the functionality.

### What if I run into any bugs, how do I report them?

For now, if you run into any bugs please either add them to the [Github Issue Tracker](https://github.com/dashboardq/feedriv/issues) or to the [DashboardQ Support System](https://www.dashboardq.com/support).

At some point, I may move to a different issue tracker, but for now, either of those should work.


### Where is the Docker image?

I don't personally use Docker right now. It may be something I add in the future, but right now I'm not looking to add additional code to the project that I don't use. Feel free to create your own Docker image.


### You know someone could rip you off and start competing with you right? You should have used a different open source license.

I'm a strong supporter of the MIT license and am completely aware that it gives someone the right to fully host and compete with my FeedRiv service. My belief is that the majority of the people will want to use the original creator's service and not a copycat service. Please feel free to use, compete, copy, any part of my service. I believe the marketplace is large enough to accomodate multiple companies.

I also think that as a solo developer, I have an ability to react quicker and make changes (change course quicker) as needed than a larger company.

If you do launch a service, the one thing I would ask is that you do not confuse the marketplace by claiming to be "FeedRiv", use the FeedRiv logo, or use any trademarked material - please use a different name and logo if you are offering the service publically for others. Feel free to copy any and all other marketing materials in this repository for any startup you may be working on.

There are countries where I can't reach or easily market to and there are definitely languages I don't speak where I would have no options to provide support. You have my complete and total permission to rip off any part of this project and use it as you see fit. (sidenote, internationalization is not setup just yet - I'm hoping to add it in steps as the core web framework is developed)

I know what it is like to be a freelancer and struggle to pay the bills. If this project allows you to charge more to clients by giving them a white-label RSS reader, by all means, load this up and start charging more!

The one final note I would make is that launching a profitable software service is very difficult (I've been trying for 17+ years). If you are looking to copy my FeedRiv service, I think it may be harder to achieve success than you realize. If you are wanting to get into building software, I would recommend building a unique tool that solves a problem that you have and/or a problem that you are very familiar with. As you can probably tell, I'm very stubborn and love to compete. If you would like to compete directly, welcome to the competition!

### OK, lets be honest, the answers to that last question is really just a big marketing gimmick, right?

I definitely believe that being free and open can have a significant positive impact on your marketing. In conversations with others, I've brought up the names of software companies I probably wouldn't have simply because they are open about their numbers. Being open helps provide reference points for others and when you become a reference point, that means more people are going to reference you. I'm a big believer in looking for win-win situations.

### Can I steal all your marketing material and create my own SaaS?

Yep. All I ask is that if you are marketing a competing service that you not use the FeedRiv name or logo or make any claims of copyright to material I've written (feel free to use it however you want, just don't claim that you wrote it - you don't need to explicitly reference FeedRiv anywhere publicly or privately).


### You are going to go out of business because you make terrible business decisions.

That is not a question, but to answer, I hope not. Follow me on my blog ([Anthony Graddy Blog](https://www.agraddy.com/)) or on [Twitter](https://twitter.com/agraddy) and watch me make more terrible business decisions. If nothing else, it should be fun and entertaining!


