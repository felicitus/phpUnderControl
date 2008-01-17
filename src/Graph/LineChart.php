<?php
/**
 * This file is part of phpUnderControl.
 * 
 * PHP Version 5.2.4
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
 * @package   Graph
 * @author    Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright 2007-2008 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://www.phpundercontrol.org/
 */

/**
 * Displays a metrics line chart.
 *
 * @category  QualityAssurance
 * @package   Graph
 * @author    Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright 2007-2008 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://www.phpundercontrol.org/
 * 
 * @property phpucAbstractInput $input The input data source.
 */
class phpucLineChart extends ezcGraphLineChart implements phpucChartI
{
    protected $showSymbol = false;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->init();
    }
    
    /**
     * Sets the input instance for the next rendering process.
     *
     * @param phpucAbstractInput $input The input object.
     * 
     * @return void
     */
    public function setInput( phpucAbstractInput $input )
    {
        $this->title        = $input->title;
        $this->yAxis->label = $input->yAxisLabel;
        $this->xAxis->label = $input->xAxisLabel;
        
        $this->data = new ezcGraphChartDataContainer( $this );
        
        foreach ( $input->data as $label => $data )
        {
            $this->data[$label]         = new ezcGraphArrayDataSet( $data );
            $this->data[$label]->symbol = ezcGraph::BULLET;

            if ( $this->showSymbol === true )
            {
                continue;
            }
            
            foreach ( $this->data[$label] as $key => $v )
            {
                $this->data[$label]->symbol[$key] = ezcGraph::NO_SYMBOL;
            }
        }
    }
    
    protected function init()
    {
        $this->palette = new phpucGraphPalette();
        
        $this->renderer->options->legendSymbolGleam = .3;
        
        $this->options->symbolSize    = 1;
        $this->options->lineThickness = 1;
        $this->options->fillLines     = 220;
        
        $this->initAxis();
        $this->initTitle();
        $this->initLegend();
    }
    
    protected function initTitle()
    {
        $this->title->background  = '#d3d7cf';
        $this->title->padding     = 1;
        $this->title->margin      = 1;
        $this->title->border      = '#555753';
        $this->title->borderWidth = 1;
    }
    
    protected function initLegend()
    {
        $this->legend->position    = ezcGraph::BOTTOM;
        $this->legend->padding     = 2;
        $this->legend->margin      = 1;
        $this->legend->border      = '#555753';
        $this->legend->borderWidth = 1;
    }
    
    protected function initAxis()
    {
        $this->yAxis->axisLabelRenderer = new ezcGraphAxisCenteredLabelRenderer();
        $this->yAxis->font->maxFontSize = 10;

        $this->xAxis                    = new ezcGraphChartElementNumericAxis();
        $this->xAxis->axisLabelRenderer = new ezcGraphAxisCenteredLabelRenderer();
        $this->xAxis->font->maxFontSize = 10;
    }
}