#FreshForm Grunt Project Base
This project includes a base for any theme using Node.js, Grunt, Bower, and Foundation. This project assumes that at least Node.js is installed.

If Grunt CLI and Bower are already installed, skip ahead to _Install Dependencies_.

##Installing Grunt
```
$ npm install -g grunt-cli
```

##Installing Bower
```
$ npm install -g bower
```

##Install Dependencies
```
$ npm install && bower install
```

##Run Grunt Tasks
Default grunt task, builds CSS and JS

```
$ grunt
```

Build CSS (sass_globbing, sasslint, sass, concat:css)

```
$ grunt buildCSS
```

Build JS (jshint, concat:js)

```
$ grunt buildJS
```

Build for deployment release CSS and JS and minify/uglify

```
$ grunt buildRelease
```

Watch files and run associated tasks

```
$ grunt watch
```
