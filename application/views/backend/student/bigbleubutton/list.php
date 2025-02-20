<?php
$school_id = school_id();
$meetings = $this->db->get_where('sessions_meetings', array('school_id' => $school_id ))->result_array();

$classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array();
$rooms = $this->db->get_where('rooms', array('school_id' => $school_id,'Etat' => 1))->result_array();

?>


<style>
        .meeting-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: 0.3s;
        }
        .meeting-card:hover {
            box-shadow: 4px 4px 15px rgba(0,0,0,0.2);
        }
        .meeting-btn {
            width: 100%;
        }
        .meeting-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .meeting-time {
            color: #777;
            font-size: 14px;
        }
    </style>

<div class="container mt-4">
    <!-- <div class="d-flex justify-content-between">
        <input type="text" class="form-control w-25" placeholder="🔍 Search">
        <a href="<?php // echo base_url('bigbluebutton/create'); ?>" class="btn btn-primary">+ New Room</a>
    </div> -->
    <div class="d-flex justify-content-between mt-3">
    <input type="text" class="form-control w-25" placeholder="🔍 Search">
</div>



    
    <!-- <div class="row mt-4">
        <?php // foreach ($meetings as $meeting):
           // $name = $this->db->get_where('classes', array('id' => $meeting['class_id']))->row('name');
            ?>
            <div class="col-md-4">
                <div class="meeting-card">
                    <div class="meeting-title"><?php //echo $meeting['name']; ?>  </div>
                    <div class="meeting-time"> Classe : <?php //echo $name; ?> </div>
                    <div class="meeting-time">Last Session: <?php //echo date("F j, Y \at h:i A", strtotime($meeting['start_time'])); ?></div>
                    <a href="<?php //echo base_url('bigbluebutton/join_meeting/' . $meeting['meeting_id']); ?>" target="_blank" class="btn btn-success meeting-btn mt-2">
                        Start
                    </a>
                </div>
            </div>
        <?php //endforeach; ?>
    </div> -->
    <!-- <div class="row mt-4">
        <?php 
        // foreach ($meetings as $meeting):
        //     $className = $this->db->get_where('classes', array('id' => $meeting['class_id']))->row('name');
        //     $status = '<span class="badge bg-danger">Non Démarrée</span>'; // Par défaut, réunion non démarrée
        ?>
            <div class="col-md-4">
                <div class="meeting-card">
                    <div class="meeting-title"><?php //echo $meeting['name']; ?></div>
                    <div class="meeting-time">Classe : <?php //echo $className; ?></div>
                    <div class="meeting-time">Last Session: <?php //echo date("F j, Y \at h:i A", strtotime($meeting['start_time'])); ?></div>
                    <div class="meeting-status" id="status-<?php //echo $meeting['meeting_id']; ?>"><?php //echo $status; ?></div>
                    <a href="<?php //echo base_url('bigbluebutton/join_meeting/' . $meeting['meeting_id']); ?>" 
                    target="_blank" 
                    class="btn btn-success meeting-btn mt-2 join-btn"
                    id="join-btn-<?php //echo $meeting['meeting_id']; ?>"
                    data-meeting-id="<?php //echo $meeting['meeting_id']; ?>">
                        Start
                    </a>
                </div>
            </div>
        <?php //endforeach; ?>
    </div> -->

    <!-- <div class="row mt-4"> -->
    <?php //foreach ($rooms as $room):
        //$className = $this->db->get_where('classes', array('id' => $room['class_id']))->row('name');
        //$status = '<span class="badge bg-danger">Non Démarrée</span>'; // Par défaut, réunion non démarrée
    ?>
        <!-- <div class="col-md-4">
            <div class="meeting-card">
                <div class="meeting-title"><?php // echo $room['name']; ?></div>
                <div class="meeting-time">Classe : <?php //echo $className; ?></div> -->
                <!-- <div class="meeting-time">Last Session: <?php // echo date("F j, Y \at h:i A", strtotime($meeting['start_time'])); ?></div> -->
                <!-- <div class="meeting-status" id="status-<?php //echo $room['id']; ?>"><?php //echo $status; ?></div>

                <div class="d-flex justify-content-between align-items-center mt-2"> -->
<!-- 
                    <a href="<?php //echo base_url('bigbluebutton/create/'. $room['id']); ?> "  
                    target="_blank" 
                    class="btn btn-success meeting-btn  join-btn"
                    id="join-btn-<?php // echo $meeting['meeting_id']; ?>"
                    data-meeting-id="<?php // echo $meeting['meeting_id']; ?>">
                        Start
                    </a> -->
                    <!-- Bouton de copie -->
                        <!-- <button class="btn btn-outline-secondary copy-btn" 
                                data-url="<?php // echo base_url('bigbluebutton/join_meeting/' . $meeting['meeting_id']); ?>" 
                                title="Copier le lien">
                            📋
                        </button>
                </div>
                
            </div>
        </div> -->
    <?php //endforeach; ?>
<!-- </div> -->

<!-- <div class="row mt-4"> -->
    <?php // foreach ($rooms as $room): ?>
        <!-- <div class="col-md-4"> -->
            <!-- <div class="meeting-card">
                <div class="meeting-title"><?php //echo $room['name']; ?></div>
                <div class="meeting-time">Classe : <?php //echo $this->db->get_where('classes', ['id' => $room['class_id']])->row('name'); ?></div>
                <div class="meeting-status" id="status-<?php //echo $room['id']; ?>">
                    <span class="badge bg-danger">Non Démarrée</span>
                </div> -->

                <!-- <div class="d-flex justify-content-between align-items-center mt-2">
                    <a  target="_blank"  href="<?php // echo base_url('bigbluebutton/create/' . $room['id']); ?>"  
                       class="btn btn-success meeting-btn join-btn"
                       id="join-btn-<?php // echo $room['id']; ?>"
                       data-meeting-id="<?php // echo $room['id']; ?>">
                        Start
                    </a> -->

                    <!-- Bouton de copie -->
                    <!-- <button class="btn btn-outline-secondary copy-btn" 
                            data-url="<?php // echo base_url('bigbluebutton/join/' . $room['id']); ?>" 
                            title="Copier le lien">
                        📋
                    </button>
                </div>
            </div>
        </div> -->
    <?php //endforeach; ?>
<!-- </div> -->
<!-- <?php //foreach ($rooms as $room): ?>
    <div class="col-md-4">
        <div class="meeting-card" data-meeting-id="<?php  //echo $room['id']; ?>">
            <div class="meeting-title"><?php  //echo $room['name']; ?></div>
            <div class="meeting-time">Classe : <?php  //echo $room['class_id']; ?></div>
            <div class="meeting-status" id="status-<?php //echo $room['id']; ?>">
                <span class="badge bg-danger">Non Démarrée</span>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-2">
                <a target="_blank" href="<?php //echo base_url('bigbluebutton/start_meeting/' . $room['id']); ?>"  
                   class="btn btn-success meeting-btn join-btn" 
                   id="join-btn-<?php // echo $room['id']; ?>">
                    Start
                </a>
                <button class="btn btn-outline-secondary copy-btn" 
                        data-url="<?php // echo base_url('bigbluebutton/join_meeting/' . $room['id']); ?>" 
                        title="Copier le lien">
                    📋
                </button>
            </div>
        </div>
    </div>
<?php // endforeach; ?> -->




<div class="row mt-4">
    <?php foreach ($rooms as $room):
        $className = $this->db->get_where('classes', array('id' => $room['class_id']))->row('name');
        $status = '<span class="badge bg-danger">Non Démarrée</span>'; // Par défaut, réunion non démarrée
    ?>
        <div class="col-md-4">
            <div class="meeting-card">
                <div class="meeting-title"><?php echo $room['name']; ?></div>
                <div class="meeting-time">Classe : <?php echo $className; ?></div>
                <div class="meeting-status" id="status-<?php echo $room['id']; ?>"><?php echo $status; ?></div>

                <!-- Nombre de participants -->
                <div class="meeting-participants" id="participants-<?php echo $room['id']; ?>">👥 0 participants</div>
                

                <div class="d-flex justify-content-between align-items-center mt-2">
                    <!-- Bouton "Join" désactivé par défaut -->
                    <a href="#" 
                    target="_blank" 
                    class="btn btn-secondary meeting-btn join-btn disabled"
                    id="join-btn-<?php echo $room['id']; ?>"
                    data-meeting-id="<?php echo $room['id']; ?>">
                        Join 
                    </a>

                    <!-- Bouton de copie du lien -->
                    <button class="btn btn-outline-secondary copy-btn d-none" 
                            id="copy-btn-<?php echo $room['id']; ?>"
                            title="Copier le lien">
                        📋
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>







</div>
	
<!-- <script type="text/javascript">
    initDataTable('basic-datatable');
</script> -->
