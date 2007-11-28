<?php
/**
 * This file is part of phpUnderControl.
 *
 * Copyright (c) 2007, Manuel Pichler <mapi@manuel-pichler.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Manuel Pichler nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 * 
 * @package    phpUnderControl
 * @subpackage Tasks
 * @author     Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright  2007 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id$
 * @link       http://www.phpunit.de/wiki/phpUnderControl
 */

/**
 * <...>
 *
 * @package    phpUnderControl
 * @subpackage Tasks
 * @author     Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright  2007 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.phpunit.de/wiki/phpUnderControl
 * 
 * @property-read boolean $metrics  Enable metrics support?
 * @property-read boolean $coverage Enable coverage support?
 */
class phpucProjectTask extends phpucAbstractTask
{
    
    public function validate()
    {
        
    }
    
    public function execute()
    {
        $installDir  = $this->args->getArgument( 'cc-install-dir' );
        $projectName = $this->args->getOption( 'project-name' );
        $projectPath = sprintf( '%s/projects/%s', $installDir, $projectName );
        
        if ( file_exists( $projectPath ) )
        {
            throw new phpucExecuteException( 'Project directory already exists.' );
        }
        
        echo 'Performing project task.' . PHP_EOL;        
        
        printf( '  1. Creating project directory: project/%s%s', $projectName, PHP_EOL );
        mkdir( $projectPath );
        
        printf( '  2. Creating source directory:  project/%s/source%s', $projectName, PHP_EOL );
        mkdir( $projectPath . '/source' );
        
        printf( '  3. Creating build directory:   project/%s/build%s', $projectName, PHP_EOL );
        mkdir( $projectPath . '/build' );
        
        printf( '  4. Creating log directory:     project/%s/build/logs%s', $projectName, PHP_EOL );
        mkdir( $projectPath . '/build/logs' );
        
        printf( '  5. Creating build file:        project/%s/build.xml%s', $projectName, PHP_EOL );
        
        $buildFile = new phpucBuildFile( $projectPath . '/build.xml', $projectName );
        $buildFile->save();
        
        echo '  6. Creating backup of file:    config.xml.orig' . PHP_EOL;
        @unlink( $installDir . '/config.xml.orig' );
        copy( $installDir . '/config.xml', $installDir . '/config.xml.orig' );
        
        echo '  7. Searching ant directory' . PHP_EOL;
        if ( count( $ant = glob( sprintf( '%s/apache-ant*', $installDir ) ) ) === 0 )
        {
            throw new phpucExecuteException( 'ERROR: Cannot locate ant directory.' );
        }
        $anthome = basename( array_pop( $ant ) );
        
        echo '  8. Modifying project file:     config.xml' . PHP_EOL;
        
        $configXml = new DOMDocument();
        $configXml->preserveWhiteSpace = false;
        $configXml->load( $installDir . '/config.xml' );
        
        $projectXml = new DOMDocument();
        $projectXml->preserveWhiteSpace = false;
        $projectXml->load( PHPUC_DATA_DIR . '/template/project.xml' );
        
        $project = $projectXml->documentElement;
        $project->setAttribute( 'name', $projectName );
        
        $schedule = $projectXml->getElementById( 'schedule' );
        $schedule->setAttribute( 'interval', $this->args->getOption( 'schedule-interval' ) );
        $schedule->removeAttribute( 'xml:id' );
        
        $ant = $projectXml->getElementById( 'ant' );
        $ant->setAttribute( 'anthome', $anthome );
        $ant->removeAttribute( 'xml:id' );
        
        $project = $configXml->importNode( $projectXml->documentElement, true );
        $configXml->documentElement->appendChild( $project );
        
        $configXml->formatOutput = true;
        $configXml->save( $installDir . '/config.xml' );
                
        echo PHP_EOL;
    }
}