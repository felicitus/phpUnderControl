/**
 * This file is part of phpUnderControl.
 *
 * Copyright (c) 2007-2009, Manuel Pichler <mapi@phpundercontrol.org>.
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
 * @category QualityAssurance
 * @author Manuel Pichler <mapi@phpundercontrol.org>
 * @category 2007-2009 Manuel Pichler. All rights reserved. 
 * @version SVN: $Id$
 */

package org.phpundercontrol.dashboard;

import net.sourceforge.cruisecontrol.ProjectState;

/**
 * Data object for a single project status.
 * 
 * @category QualityAssurance
 * @author Manuel Pichler <mapi@phpundercontrol.org>
 * @category 2007-2009 Manuel Pichler. All rights reserved. 
 * @version SVN: $Id$
 */
public class BuildStatus {

	/**
	 * The project build state.
	 */
	private final ProjectState state;

	/**
	 * String representation of the project build state.
	 */
	private final String importance;

	/**
	 * Constructs a new build status instance.
	 *  
	 * @param state The project build state.
	 * @param importance String representation of the build state.
	 */
	public BuildStatus(ProjectState state, String importance) {
		this.state = state;
		this.importance = importance;
	}

	/**
	 * Returns the importance string.
	 * 
	 * @return The importance string.
	 */
	public String getImportance() {
		return importance;
	}

	/**
	 * Returns the string representation of the object.
	 */
	public String toString() {
		return state != null ? state.getName() : "?";
	}
}