# 大学SIS (daxueSIS-electron) - University Student Information System built with Electron
<p align="center">
<img src="https://github.com/YamiND/daxuesis-electron/blob/master/assets/icons/icon_512x512.png?raw=true" alt="daxueSIS-electron Icon"/>
</p>

## Introduction
A self-contained SIS using electron hosted on your own system. This is the result of having limited resources in China
It is primarily made with electron, jquery, html, css, nodejs components, and a few other addons

Originally I had this project follow a client-server model with it being accessed via a regular web browser, however I did not anticipate I would not have internet access in every classroom here in China. It is cross-platform, and easily portable.

## Setup
You will need to have a proper node.js environment + npm setup. Once you have done that, please run the following command(s)
```
npm run-script rebuild // Rebuild the modules for running on Windows, macOS, or Linux
npm run-script start // Runs 大学SIS in the CWD 
npm run-script build-mac // Builds a .app that should be portable
npm run-script build-win // Builds a .exe with dependencies for being portable on Windows 
```

Builds by default will be saved to a release-builds directory. From there you can copy that folder to wherever you need to run it from

