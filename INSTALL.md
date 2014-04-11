# Commandline Installation

During installation of TYPO3.Neos through the web interface, the installer invokes 
a command line controller that executes, beside many other tasks, the following 
commands by invoking the PHP ```exec``` function.

The following commands are executed, always assuming the $ROOT points to the root 
of the TYPO3.Neos installation, that can be ```/opt/appserver/webapps/neos-1.0.2```
for example:

```
FLOW_ROOTPATH='$ROOT/' FLOW_CONTEXT='Production' "/opt/appserver/bin/php" -c '/opt/appserver/etc/php.ini' '$ROOT/Packages/Framework/TYPO3.Flow/Scripts/flow.php' 'typo3.flow:core:compile'
FLOW_ROOTPATH='$ROOT/' FLOW_CONTEXT='Production' "/opt/appserver/bin/php" -c '/opt/appserver/etc/php.ini' '$ROOT/Packages/Framework/TYPO3.Flow/Scripts/flow.php' 'typo3.flow:doctrine:compileproxies'
FLOW_ROOTPATH='$ROOT/' FLOW_CONTEXT='Production' "/opt/appserver/bin/php" -c '/opt/appserver/etc/php.ini' '$ROOT/Packages/Framework/TYPO3.Flow/Scripts/flow.php' 'typo3.flow:doctrine:migrate'
```