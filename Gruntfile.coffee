module.exports = (grunt) ->
  grunt.initConfig
    pkg: grunt.file.readJSON('package.json')
    banner: '/*!\n' +
             ' * <%= pkg.name %> v<%= pkg.version %> by <%= pkg.author %>\n' +
             ' * Copyright <%= grunt.template.today("yyyy") %> <%= pkg.copyright %>\n' +
             ' * Licensed under <%= pkg.license %>\n' +
             ' */'

    replace: {
      xml: {
        options: {
          expression: false
          
          patterns: [
            {
              match: 'name'
              replacement: '<%= pkg.name %>'
            },
            { 
              match: 'version'
              replacement: '<%= pkg.version %>'
            },
            {
              match: 'description'
              replacement: '<%= pkg.description %>'
            },
            {
              match: 'description'
              replacement: '<%= pkg.author%>'
            },
            {
              match: 'description'
              replacement: '<%= pkg.copyright %>'
            },
            {
              match: 'description'
              replacement: '<%= pkg.authorEmail %>'
            },
            {
              match: 'description'
              replacement: '<%= pkg.creationDate %>'
            },
            {
              match: 'description'
              replacement: '<%= pkg.authorUrl %>'
            },
            {
              match: 'description'
              replacement: '<%= pkg.license %>'
            }
            
          ]
        }
        files: [
          {
            expand: true
            flatten: true
            src: '_source/mod_stack.xml'
            dest: ''
          }
        ]
      }
    }

    sass:
      options:
        precision: 5
        sourcemap: false
      build:
        files: [{
          expand: true,
          cwd:  '_source/css/',
          dest: '.tmp/css/',
          src:  ['*.scss', '!_*.scss']
          ext:  '.css'
        }]

    autoprefixer:
      options:
        browsers: ['last 2 version', 'ie 9', '> 1%']
      build:
        expand: true
        # flatten: true
        cwd: '.tmp/css/'
        src: '**/*.css'
        dest: '.tmp/css/'

    copy:
      css:
        expand: true
        cwd: '.tmp/css'
        src: ['**/*.css']
        dest: 'media/css/'
      php:
        expand: true
        cwd: '_source/'
        src: ['**/*.php', '**/*.html']
        dest: ''
      jsTmp:
        expand: true
        cwd: '_source/js/'
        src: ['**/*.js', '**/*.coffee']
        dest: '.tmp/js/'
      js:
        expand: true
        cwd: '.tmp/js/'
        src: '**/*.js'
        dest: 'media/js'

    watch:
      css:
        files: ['_source/**/*.scss', '_source/**/*.css']
        tasks: ['sass', 'autoprefixer', 'copy:css']
      cssPhp:
        files: '_source/**/*.css.php'
        tasks: 'copy:cssPhp'
      php:
        files: ['_source/**/*.php', '_source/**/*.html']
        tasks: 'copy:php'
      xml:
        files: ['_source/**/*.xml']
        tasks: 'replace:xml'

    # release

  # !Load Tasks
  require("load-grunt-tasks") grunt

  grunt.registerTask 'default', [
    # compile sass to _build/css
    'sass'
    'autoprefixer'
    # compile js to _build/js
    'copy:jsTmp'
    'copy:js'
    # copy php from src to _build
    'copy:php'
    # 'copy:xml'
    'copy:css'
    'replace:xml'
    'watch'
  ]


  grunt.registerTask 'release', [
    # copy build folder to release
    # compress images
    # compress icons
    # compress css
    # compress js
    # add copyrights to js/css
    # tar.gz bundle - name as version - to desktop
  ]