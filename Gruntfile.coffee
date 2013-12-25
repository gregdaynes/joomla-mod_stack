module.exports = (grunt) ->
  grunt.initConfig
    pkg: grunt.file.readJSON('package.json')
    banner: '/*!\n' +
             ' * <%= pkg.name %> v<%= pkg.version %> by <%= pkg.author %>\n' +
             ' * Copyright <%= grunt.template.today("yyyy") %> <%= pkg.copyright %>\n' +
             ' * Licensed under <%= pkg.license %>\n' +
             ' */'

    sass:
      options:
        precision: 5
        sourcemap: false
      build:
        files: [{
          expand: true,
          cwd:  'src/css/',
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
        dest: '_build/media/css/'
      php:
        expand: true
        cwd: 'src/'
        src: ['**/*.php', '**/*.html']
        dest: '_build/'
      xml:
        expand: true
        cwd: 'src/'
        src: '**/*.xml'
        dest: '_build/'

    watch:
      css:
        files: ['src/**/*.scss', 'src/**/*.css']
        tasks: ['sass', 'autoprefixer']
      php:
        files: ['src/**/*.php', 'src/**/*.html']
        tasks: 'copy:php'
      xml:
        files: ['src/**/*.xml']
        tasks: 'copy:xml'

    # release

  # !Load Tasks
  require("load-grunt-tasks") grunt

  grunt.registerTask 'default', [
    # compile sass to _build/css
    'sass'
    'autoprefixer'
    # compile coffeescript to _build/js
    # copy php from src to _build
    'copy:php'
    'copy:xml'
    'copy:css'
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