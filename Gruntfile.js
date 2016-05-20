'use strict';

module.exports = function (grunt) {
	require('load-grunt-tasks')(grunt);
	grunt.verbose.writeln('begin task...');

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		app: 'app',
		dist: 'dist',
		bower: {
			all: {
				dest: '<%= app %>/',
				js_dest: '<%= app %>/scripts/lib',
				scss_dest: '<%= app %>/scss/',
				css_dest: '<%= app %>/css',
				fonts_dest: '<%= app %>/fonts',
				images_dest: '<%= app %>/images',
				options: {
					keepExpandedHierarchy: false,
					expand: false,
					ignorePackages: [
						'underscore',
						'font-awesome-animation'
					],
					packageSpecific: {
						'foundation-sites': {
							'files': [
								'dist/foundation.js'
							]
						},
						'react': {
							'files': ['react.js', 'react-dom.js']
						},
						'motion-ui': {
							'files': ['dist/motion-ui.js']
						},
						'lodash': {
							'files': ['lodash.js']
						},
						'PubSub': {
							'files': ['pubsub.js']
						},
						'font-awesome': {
							'files': ['fonts/**']
						},
						'font-awesome-animation': {
							'files': ['dist/font-awesome-animation.css']
						},
						'highcharts': {
							'files': ['highcharts.js']
						},
					}
				}
			}
		},
		clean: {
			dist: {
				src: ['<%= dist %>/*']
			},
		},
		copy: {
			dist: {
				files: [{
					expand: true,
					cwd: '<%= app %>/',
					src: ['i18n/**', 'fonts/**', '**/*.html', 'images/**', '!**/*.scss'],
					dest: '<%= dist %>/'
				}]
			},
			python: {
				files: [{
					expand: true,
					cwd: '',
					src: ['app.yaml', 'dashboard.py'],
					dest: '<%= dist %>/'
				}]
			}
		},
		sass: {
			dist: {
				options: {
					style: 'expanded', // expanded or nested or compact or compressed
					loadPath: [
						'bower_components/foundation-sites/scss',
						'bower_components/motion-ui/src',
						'bower_components/font-awesome/scss'
					],
					compass: true,
					quiet: true
				},
				files: {
					'<%= app %>/css/library.css': '<%= app %>/scss/library.scss',
					'<%= app %>/css/app.css': '<%= app %>/scss/app.scss',
				}
			}
		},
		useminPrepare: {
			html: ['<%= app %>/*.html'],
			options: {
				dest: '<%= dist %>'
			}
		},
		uglify: {
			options: {
				preserveComments: 'some',
				mangle: false,
				compress: {
					global_defs: {
						DEBUG: true // That very variable
					}
				}
			}
		},
		babel: {
			options: {
				sourceMap: false,
				presets: ['es2015', 'react'],
				plugins: ['transform-react-jsx'],
			},
			dist: {
				files: [{
					src: ['<%= app %>/scripts/views/baseView.jsx'],
					dest: '<%= app %>/scripts/views/baseView.js',
				}, {
					src: ['<%= app %>/scripts/views/loading.jsx'],
					dest: '<%= app %>/scripts/views/loading.js',
				}, {
					src: ['<%= app %>/scripts/views/layout.jsx'],
					dest: '<%= app %>/scripts/views/layout.js',
				}, {
					src: ['<%= app %>/scripts/views/dashboard/index.jsx'],
					dest: '<%= app %>/scripts/views/dashboard/index.js',
				}]
			}
		},
		filerev: {
			options: {
				algorithm: 'md5',
				length: 8
			},
			files: {
				src: [
					'<%= dist %>/scripts/**/*.js',
					'<%= dist %>/css/**/*.css'
				]
			}
		},
		usemin: {
			html: ['<%= dist %>/**/*.html'],
			css: ['<%= dist %>/css/**/*.css'],
			options: {
				dirs: ['<%= dist %>']
			}
		},
		watch: {
			grunt: {
				files: ['Gruntfile.js'],
				tasks: ['bower', 'sass', 'babel']
			},
			sass: {
				files: '<%= app %>/scss/**/*.scss',
				tasks: ['sass']
			},
			babel: {
				files: '<%= app %>/scripts/**/*.jsx',
				tasks: ['babel']
			},
			livereload: {
				files: ['<%= app %>/**/*.html', '<%= app %>/scripts/**/*.js', '<%= app %>/css/**/*.css', '<%= app %>/images/**/*.{jpg,gif,svg,jpeg,png}'],
				options: {
					livereload: true
				}
			}
		}
	});

	grunt.registerTask('debug', ['bower', 'sass', 'babel']);
	grunt.registerTask('release', ['debug', 'clean:dist', 'useminPrepare', 'copy:dist', 'concat', 'cssmin', 'uglify', 'filerev', 'usemin', 'copy:python']);
	grunt.registerTask('default', ['debug']);

};