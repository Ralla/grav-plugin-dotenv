# Grav .ENV Plugin

Simple [Grav](http://github.com/getgrav/grav) plugin that provides a simple way to load environment variables from .env to getenv(), $_ENV and $_SERVER.

# Installation

Installing the plugin can be done in one of two ways. Our GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file. 

## GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's Terminal (also called the command line). From the root of your Grav install type:

    bin/gpm install dotenv

This will install the plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/dotenv`.

## Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `dotenv`.

You should now have all the plugin files under:

    /your/site/grav/user/plugins/dotenv

# Usage

In order to use the plugin, create the file `.gravenv` in the root of your project directory. Here's an example of overriding the existing system configuration:

Current contents of `system.yaml`file:

```
cache:
  enabled: true
twig:
  cache: tue
debugger:
  enabled: false
```

Contents of the `.gravenv` file:

```
system.cache.enabled=false
system.twig.cache=false
system.debugger.enabled=true
```
