module.exports = function(grunt) {
    'use strict';

    var skinDir = '../skin/frontend/integento/default/';

    // load tasks
    grunt.loadNpmTasks('grunt-imageoptim');
    grunt.loadNpmTasks('grunt-contrib-compass');

    grunt.initConfig({
        imageoptim: {
            dist: {
                src: [skinDir + 'images/*.png']
            }
        },
        compass: {
            dist: {
                options: {
                    config: 'config.rb'
                }
            }
        }
    });

    // alias
    grunt.registerTask('default', ['compass', 'imageoptim']);
};