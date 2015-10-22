# Setup

## Overview

Since this project will be hosted on the [WordPress VIP](https://vip.wordpress.com/) platform 
the environment setup is a bit different than other WordPress 
projects. The VIP platform is highly customized so, to aid developers, 
the WordPress team created a "Quickstart" VM environment. This 
environment contains the WordPress core as well as the plugins
available for use on the VIP platform.

You can obtain the VIP Quickstart vm from the Automattic (makers
of WordPress) [GitHub repo](https://github.com/Automattic/vip-quickstart).

The VIP environment is a massive __multi-site__ install and, as such,
is very restrictive. All code is reviewed before it is pushed to
production for *any* site.

More information for developing on the VIP platform can be found in
the [VIP Documentation](https://vip.wordpress.com/documentation/) especially in the [Developers Guide](https://vip.wordpress.com/documentation/developers-guide-to-wordpress-com-vip/).

## Getting started

The first thing you need to do is clone yourself a copy of the VIP 
Quickstart environment (as quickstart in this example):

    $> cd ~/projects/
    $> git clone --recursive https://github.com/Automattic/vip-quickstart internetdotorg

Once you have your copy checked out you can start the vagrant instance,
please note, this will take a long time to start the first time:

    $> cd internetdotorg
    $> vagrant up
    
Depending on how you have vagrant configured in your environment you may need to
add the following to your [hosts](http://en.wikipedia.org/wiki/Hosts_\(file\)) file:

    10.86.73.80    vip.local

## Creating/adding your theme

Your theme, as well as any "extra" plugins you wish to us on your site, 
will need to be put in a special directory within your wp-content/themes directory.
There is a directory within the themes directory called "vip" in this directory
is where you will put your theme (notice a directory named "plugins" is already
there, these are the plugins you can use on your site).

Your theme is the only thing allowed with your project's repo which is why plugins
should be placed within unless you set up a separate repo. You should not that
nothing outside of your theme's directory is allowed in the repo, including any
site config files, WordPress core code nor the plugins available by default. This
keeps your repo light and the amount of code to be reviewed by the VIP team to a
minimum.

To add your thime to the environment:

	$> cd ~/projects/internetdotorg/www/wp-content/themes/vip
	$> git clone --recursive https://git.nerderylabs.com/FI.INTERNETORG internetorg
	$> cd internetorg
	$> git checkout develop

## Plugins

There is a large number of plugins VIP has [pre-approved](https://vip.wordpress.com/plugins/) for use on your 
site which are included in the quickstart, however, you are free to use any VIP
approved plugins by including them in your repo. 

There are a couple of ways you can use plugins, the first is by setting up a second
respitory location (contact your VIP rep for details) the second is by adding 
directly to your theme. Any additional plugins (even if you build them yourself) need
to be placed in a "plugins" directory within your theme.

## Coding for VIP

You must follow the guidlines set forth in the VIP Developers guide if your code
is to pass review by the VIP review team. Much of the preview process checks for
performance and security related items. Most of the time alternative solutions
are provided for simple items, for instance, if you are using an uncached function you will
be suggested an alternative method of implementation, usually the cached version of
the function or a suggested why to implement caching for the function call.

## Build Process

For this site we are using the FE boiler plate, follow the instructions provided 
[on the boilerplate homepage](https://js.nerderylabs.com/boilerplate/).

## Miscellaneous Tasks

### Purging the cache 

This environment is very heavily cached and this caching will no doubt get into your 
way at some point. Cache purging has been explicitly disabled in the VM so sending 
memcached purge requests will do you no good. In order to purge memcached you will 
need to restart memcached. First, you need to log into the VM, then you will need to
restart the service:

    $> cd quickstart
    $> vagrant ssh
    vagrant@vip:~$ sudo service memcached restart 


### PHP Xdebug Support

XDebug is installed on the VM but it is not enabled by default. To change this your will
need to log in to the VM (via ssh) and update the config files manually

### Error logs

#### PHP-FPM

The PHP FPM log can log things that may not go into your default log file. You may need to activate the PHP FPM log. This command will setup the FPM log and allow you to tail it from your local machine (ei. not ssh'd into the vagrant instance).

    $> vagrant ssh
    $> sudo ln -s /srv/php-fpm-www-error.log /var/log/php-fpm-www-error.log && sudo touch /srv/php-fpm-www-error.log && sudo chown www-data:www-data /srv/php-fpm-www-error.log
    
Then, to tail the log exit the vm (either type exit or hit ctrl+d).

	$> cd ~/projects/internetdotorg/www/
	$> tail -f php-fpm-www-error.log
	
To stop tailing a file hit ctrl+c.

#### Nginx Error Log

To view the nginx error log you need to log into the vagrant instance and tail the log file from there:

    $> cd ~/projects/internetdotorg/www/
    $> vagrant ssh
    $> cd /var/log/nginx
    $> tail -f error.log

__Note:__ Your error log may be named slightly different (e.g. _.error.log) you can `$> ls -la` in the log directory to see which files are available to you, look for the most recently updated file with error in the name.



