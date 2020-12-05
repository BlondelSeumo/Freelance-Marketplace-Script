<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

class Google_Service_TagManager_WorkspaceSidelineHistory extends Google_Model
{
  protected $commentType = 'Google_Service_TagManager_WorkspaceSidelineHistoryComment';
  protected $commentDataType = '';
  protected $createdByType = 'Google_Service_TagManager_WorkspaceSidelineUser';
  protected $createdByDataType = '';
  protected $createdTimestampType = 'Google_Service_TagManager_Timestamp';
  protected $createdTimestampDataType = '';
  protected $statusChangeType = 'Google_Service_TagManager_WorkspaceSidelineHistoryStatusChange';
  protected $statusChangeDataType = '';
  public $type;

  /**
   * @param Google_Service_TagManager_WorkspaceSidelineHistoryComment
   */
  public function setComment(Google_Service_TagManager_WorkspaceSidelineHistoryComment $comment)
  {
    $this->comment = $comment;
  }
  /**
   * @return Google_Service_TagManager_WorkspaceSidelineHistoryComment
   */
  public function getComment()
  {
    return $this->comment;
  }
  /**
   * @param Google_Service_TagManager_WorkspaceSidelineUser
   */
  public function setCreatedBy(Google_Service_TagManager_WorkspaceSidelineUser $createdBy)
  {
    $this->createdBy = $createdBy;
  }
  /**
   * @return Google_Service_TagManager_WorkspaceSidelineUser
   */
  public function getCreatedBy()
  {
    return $this->createdBy;
  }
  /**
   * @param Google_Service_TagManager_Timestamp
   */
  public function setCreatedTimestamp(Google_Service_TagManager_Timestamp $createdTimestamp)
  {
    $this->createdTimestamp = $createdTimestamp;
  }
  /**
   * @return Google_Service_TagManager_Timestamp
   */
  public function getCreatedTimestamp()
  {
    return $this->createdTimestamp;
  }
  /**
   * @param Google_Service_TagManager_WorkspaceSidelineHistoryStatusChange
   */
  public function setStatusChange(Google_Service_TagManager_WorkspaceSidelineHistoryStatusChange $statusChange)
  {
    $this->statusChange = $statusChange;
  }
  /**
   * @return Google_Service_TagManager_WorkspaceSidelineHistoryStatusChange
   */
  public function getStatusChange()
  {
    return $this->statusChange;
  }
  public function setType($type)
  {
    $this->type = $type;
  }
  public function getType()
  {
    return $this->type;
  }
}
