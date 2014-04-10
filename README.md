# Running TYPO3.Neos on appserver.io

TYPO3.Neos is ready to run on the appserver.io infrastructure right now. To run TYPO3.Neos you actually 
has two installation possiblities.

This descriptions assume variable $AS points to the root of the appserver.io AS distribution. By default
this is ```/opt/appserver``` on any Linux or Mac OS X system. 

## Installation

### Possibility 1: Deploy the PHAR archive

The first, and probably easiest way, is to deploy the latest PHAR archive you can download from the
[Releases](https://github.com/techdivision/TechDivision_ApplicationServerNeos/releases) page by copy
it to the ```$AS/deploy``` directory and create the necessary marker file with:

```
$ cd $AS/deploy
$ touch neos-1.0.2.phar.dodeploy
```

Optionally you can install the PHAR archive by using the admin panel for your appserver.io installation.

Wait until the application server has been restarted, open a browser and start the setup process by
opening the URL ```http://127.0.0.1:9080/neos-1.0.2/setup```.

### Possiblity 2: Init instance by using the apropriate ANT target

This possibility assume that you have a working ANT and a global composer installation running on your 
local machine.

ATTENTION: By calling the ANT target, you'll silently delete a installation that will be found under
```$AS/webapps/neos-1.0.2```. So use this solution only, if you've a backup or can be sure that you
don't need any files from this directory.

To run the ANT target, do the following:

```
$ cd ~
$ git clone https://github.com/techdivision/TechDivision_ApplicationServerNeos.git
$ cd TechDivision_ApplicationServerNeos
$ sudo ANT init-instance
```

After that, restart the application server and enjoy!

## Create the PHAR archive by yourself

Addionally you can create the PHAR archive with the TYPO3.Neos version (you have to specify in the
build.default.properties) by yourself. You will also need a working ANT and a global composer installation
on your local machine.

There you have to do the following steps:

```
$ cd ~
$ git clone https://github.com/techdivision/TechDivision_ApplicationServerNeos.git
$ cd TechDivision_ApplicationServerNeos
$ sudo ANT create-phar
```

You'll find the resulting PHAR archive in the ```target``` folder.