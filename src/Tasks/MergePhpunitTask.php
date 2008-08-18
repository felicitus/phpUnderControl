<?php
/**
 * This file is part of phpUnderControl.
 * 
 * PHP Version 5.2.0
 *
 * Copyright (c) 2007-2008, Manuel Pichler <mapi@manuel-pichler.de>.
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
 * @category  QualityAssurance
 * @package   Tasks
 * @author    Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright 2007-2008 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://www.phpundercontrol.org/
 */

/**
 * Merges a set of PHPUnit log files within a single log.
 *
 * @category  QualityAssurance
 * @package   Tasks
 * @author    Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright 2007-2008 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://www.phpundercontrol.org/
 */
class phpucMergePhpunitTask extends phpucAbstractTask implements phpucConsoleExtensionI
{
    /**
     * List of input log files.
     *
     * @type array<string>
     * @var array(string=>string) $inputFiles
     */
    private $inputFiles = array();

    /**
     * Validates the task constrains.
     *
     * @return void
     * @throws phpucValidateException If the validation fails.
     */
    public function validate()
    {
        $input = $this->args->getOption( 'input' );
        
        if ( is_dir( $input ) === true )
        {
            $files = glob( "{$input}/*.xml" );
        }
        else
        {
            $files = array_map( 'trim', explode( ',', $input ) );
        }

        foreach ( $files as $file )
        {
            if ( file_exists( $file ) === false )
            {
                throw new phpucValidateException(
                    sprintf(
                        'The specified --input "%s" doesn\'t exist.',
                        $file
                    )
                );
            }
        }
        
        if ( $this->args->hasOption( 'builds' ) === true )
        {
            $builds = $this->args->getOption( 'builds' );
            $builds = array_map( 'trim', explode( ',', $builds ) );
        }
        else
        {
            $builds = array();
            foreach ( $files as $file )
            {
                $builds[] = pathinfo( $file, PATHINFO_FILENAME );
            }
        }
        
        if ( count( $builds ) !== count( $files ) )
        {
            throw new phpucValidateException(
                sprintf(
                    'Number of build identifiers "%s" and files "%s" doesn\'t match.',
                    count( $builds ),
                    count( $files )
                )
            );
        }
        
        $this->inputFiles = array_combine( $builds, $files );
        
        $output = dirname( $this->args->getOption( 'output' ) );
        if ( is_dir( $output ) === false )
        {
            if ( mkdir( $output ) === false || is_dir( $output ) === false )
            {
                throw new phpucValidateException(
                    sprintf( 'Cannot create output directory "%s".', $output )
                );
            }
        }
    }
    
    /**
     * 
     * 
     * @return void
     */
    public function execute()
    {
        $inputFiles = new ArrayIterator( $this->inputFiles );
        $aggregator = new phpucPHPUnitTestLogAggregator();
        
        $aggregator->aggregate( $inputFiles );
        $aggregator->store( $this->args->getOption( 'output' ) );
    }
    
    /**
     * Callback method that registers a command extension. 
     *
     * @param phpucConsoleInputDefinition $def 
     *        The input definition container.
     * @param phpucConsoleCommandI  $command
     *        The context cli command instance.
     * 
     * @return void
     */
    public function registerCommandExtension( phpucConsoleInputDefinition $def,
                                              phpucConsoleCommandI $command ) 
    {
        $def->addOption(
            $command->getCommandId(),
            'i',
            'input',
            'List of input log files(separated by comma) or a single log ' .
            'directory with multiple log files.',
            true,
            null,
            true
        );
        $def->addOption(
            $command->getCommandId(),
            'o',
            'output',
            'The output log file.',
            true,
            null,
            true
        );
        $def->addOption(
            $command->getCommandId(),
            'j',
            'project-name',
            'The name of the generated project.',
            true,
            null,
            true
        );
        $def->addOption(
            $command->getCommandId(),
            'b',
            'builds',
            'Optional list of build identifiers(separated by comma). This ' .
            'option can be used together with a comma separated list of log ' .
            'files',
            true
        );
    }
}