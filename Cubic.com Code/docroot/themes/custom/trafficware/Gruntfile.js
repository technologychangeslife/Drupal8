/* globals module, require */
module.exports = function (grunt) {
  'use strict';
  // Matchdep saves you from adding all your grunt packages manually.
  require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
  var sass = require('node-sass');

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    // Concatenates css/js files.
    concat: {
      css: {
        files: {
          '_/css/<%= pkg.name %>.css': [
            '_/source/styles/style.css'
          ]
        }
      },
      js: {
        files: {
          '_/js/<%= pkg.name %>.js': [
            '_/source/scripts/script.js',
            '_/source/scripts/components.js'
          ]
        }
      }
    },
    // Copy vendor source files to build dir.
    copy: {
      css: {
        files: [
          {
            expand: true,
            flatten: true,
            cwd: './bower_components',
            src: [
              'slick-carousel/slick/slick.css',
              'outdated-browser/outdatedbrowser/outdatedbrowser.min.css'
            ],
            dest: './_/source/styles/vendor/'
          }
        ]
      },
      js: {
        files: [
          {
            expand: true,
            flatten: true,
            cwd: './bower_components',
            src: [
              'bxslider-4/dist/jquery.bxslider.min.js',
              'ellipsis.js/ellipsis.min.js',
              'foundation-sites/dist/js/plugins/foundation.accordion.min.js',
              'foundation-sites/dist/js/plugins/foundation.core.min.js',
              'foundation-sites/dist/js/plugins/foundation.equalizer.min.js',
              'foundation-sites/dist/js/plugins/foundation.reveal.min.js',
              'foundation-sites/dist/js/plugins/foundation.sticky.min.js',
              'foundation-sites/dist/js/plugins/foundation.tabs.min.js',
              'foundation-sites/dist/js/plugins/foundation.util.keyboard.min.js',
              'foundation-sites/dist/js/plugins/foundation.util.mediaQuery.min.js',
              'foundation-sites/dist/js/plugins/foundation.util.motion.min.js',
              'foundation-sites/dist/js/plugins/foundation.util.timerAndImageLoader.min.js',
              'foundation-sites/dist/js/plugins/foundation.util.triggers.min.js',
              'jarallax/dist/jarallax.min.js',
              'jquery-easing/jquery.easing.min.js',
              'jquery/dist/jquery.min.js',
              'outdated-browser/outdatedbrowser/outdatedbrowser.min.js',
              'protonet/jquery.inview/jquery.inview.min.js',
              'rellax/rellax.min.js',
              'slick-carousel/slick/slick.min.js'
            ],
            dest: './_/source/scripts/vendor/'
          }
        ]
      }
    },
    // Minifies css.
    cssmin: {
      build: {
        files: {
          '_/css/<%= pkg.name %>.min.css': ['_/css/<%= pkg.name %>.css']
        }
      }
    },
    // Copy files down via cURL.
    curl: {
      '.sass-lint.yml': 'https://bitbucket.org/!api/2.0/snippets/freshform/ookRr/2fe1f38177f192a851d5b276a5c09f97c56c70c5/files/.sass-lint.yml',
      '.jshintrc': 'https://bitbucket.org/!api/2.0/snippets/freshform/M5bGg/1a1ff185fe0b95f87459c70165b36f2793a4af69/files/.jshintrc'
    },
    // Lints js files.
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      files: ['Gruntfile.js', '_/source/scripts/*.js']
    },
    // Apply CSSNext to compiled CSS.
    postcss: {
      options: {
        processors: [
          require('postcss-cssnext')({browsers: 'last 2 versions, ie >= 9, and_chr >= 2.3, ios_saf >= 8.4'})
        ]
      },
      build: {
        src: '_/css/*.css'
      }
    },
    // Adds sass partials to style.scss.
    sass_globbing: {
      build: {
        files: {
          '_/source/styles/sass/style.scss': '_/source/styles/sass/partials/**/*.scss'
        },
        options: {
          signature: '// generated with grunt-sass-globbing',
          useSingleQuotes: true
        }
      }
    },
    // Compiles sass.
    sass: {
      options: {
        implementation: sass,
        includePaths: ['bower_components/foundation-sites/scss'],
        outputStyle: 'nested'
      },
      build: {
        files: {
          '_/source/styles/style.css': '_/source/styles/sass/style.scss'
        }
      }
    },
    // Lints sass files.
    sasslint: {
      options: {
        configFile: '.sass-lint.yml'
      },
      target: [
        '_/source/styles/sass/style.scss',
        '_/source/styles/sass/**/*.scss'
      ]
    },
    // Create icon sprite.
    svgstore: {
      options: {
        cleanup: true,
        cleanupdefs: true,
        prefix: 'icon-',
        svg: {
          style: 'display: none;'
        }
      },
      dist: {
        files: {
          'svg-defs.svg': ['_/source/svg/**/*.svg']
        }
      }
    },
    // Compresses js files.
    uglify: {
      options: {
        mangle: false
      },
      build: {
        files: {
          '_/js/<%= pkg.name %>.min.js': ['_/js/<%= pkg.name %>.js']
        }
      }
    },
    // Watch monitors files and performs tasks.
    watch: {
      css: {
        files: ['_/source/styles/**/*.scss', '_/source/styles/*/*.css', '_/source/styles/drupal.css'],
        tasks: ['buildCSS']
      },
      js: {
        files: ['Gruntfile.js', '_/source/scripts/**/*.js'],
        tasks: ['buildJS']
      },
      svg: {
        files: ['_/source/svg/**/*.svg'],
        tasks: ['buildSVG']
      }
    },
    browserSync: {
      dev: {
        bsFiles: {
          src: [
            '_/css/*.css',
            '_/js/*.js'
          ]
        },
        options: {
          watchTask: true,
          open: false,
          proxy: 'local.cubic.com'
        }
      }
    }
  });

  grunt.registerTask('default', ['curl', 'sass_globbing', 'sasslint', 'sass', 'jshint', 'copy', 'concat', 'postcss', 'svgstore']);
  grunt.registerTask('buildCSS', ['sass_globbing', 'sasslint', 'sass', 'concat:css', 'postcss']);
  grunt.registerTask('buildJS', ['jshint', 'concat:js']);
  grunt.registerTask('buildSVG', ['svgstore']);
  grunt.registerTask('buildRelease', ['default', 'uglify', 'cssmin']);
  grunt.registerTask('watchBS', ['buildCSS', 'buildJS', 'browserSync', 'watch']);
};
