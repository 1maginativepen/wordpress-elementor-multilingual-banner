<?php

        function link_affiliate_banner() {

            add_meta_box(
                'banner-link', 
                'Add Affiliate link of banner here: ',
                'banner_link_content',
                'banner',
                'normal',
                'high'
            ); 
        } 

        add_action( 'add_meta_boxes', 'link_affiliate_banner' ); 

        function banner_link_content() {  
            require_once ECW_PLUGIN_PLUGIN_PATH.'modules/generate_functions/elements/css/main.php'; 

            $_SESSION["the_ID"] = get_the_ID(); 
            ?> 
            

                <table class="table table-striped table-hover">
                        <thead>
                            <tr> 
                                <th>Detail ID</th>
                                <th>Language</th>
                                <th>Link</th>
                                <th>Image Link</th>
                                <th style="text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        <?php

                            global $wpdb;
                            $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}plugin_custom_banner WHERE banner = ".$_SESSION["the_ID"], OBJECT );  
 
                            $loop_val = 1;
                            foreach( $results as $posts ) { 
                                ?>
                                    <tr>
                                        <td>BD<?php echo $posts->ID;?></td>
                                        <td><?php echo $posts->language;?></td>
                                        <td><?php echo $posts->link;?></td>
                                        <td><?php echo $posts->image_path;?></td>
                                        <td style="text-align:center;">
                                            <a href="#editEmployeeModal<?php echo $posts->ID;?>" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                            <a href="#deleteEmployeeModal<?php echo $posts->ID;?>" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                                        </td>

                                        <!-- Edit Modal HTML -->
                                        <div id="editEmployeeModal<?php echo $posts->ID;?>" class="modal fade">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post" action="<?php $_SERVER['REQUEST_URI'];?>">
                                                        <div class="modal-header">						
                                                            <h4 class="modal-title">Update Banner Details</h4>  
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                        
                                                        <div class="form-group"> 
                                                            <label>Detail ID</label>
                                                            <input class="form-control" required name="the_ID" value="<?php echo $posts->ID;?>" readonly>
                                                        </div>

                                                        <div class="form-group">
                                                                <label>Name</label><br>
                                                                <select class="spacer" style="width:100%;" name="language-select" > 
                                                                    <?php 
                                                                        $tags = get_terms([
                                                                            'taxonomy'  => 'banner_language',
                                                                            'hide_empty'    => false
                                                                        ]);  

                                                                        foreach($tags as $get_tag){
                                                                            if($posts->language != $get_tag->name){
                                                                                echo '<option class="" value="'.$get_tag->name.'">'.$get_tag->description.'</option>';
                                                                            }else{
                                                                                echo '<option class="" value="'.$get_tag->name.'" selected="selected">'.$get_tag->description.'</option>';
                                                                            }
                                                                            
                                                                        } 
                                                                    ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group"> 
                                                            <label>Link</label>
                                                            <input class="form-control" required name="language-link" value="<?php echo $posts->link;?>">
                                                        </div>
                                                       
                                                        <div class="form-group">
                                                            <label>Image Path</label>
                                                            <textarea class="form-control" required name="image-link"><?php echo $posts->image_path;?></textarea>
                                                        </div> 	

                                                        </div>

                                                        <div class="modal-footer">
                                                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                                                            <input type="submit" class="btn btn-info" value="Update Details" name="update">
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Delete Modal HTML -->
                                        <div id="deleteEmployeeModal<?php echo $posts->ID;?>" class="modal fade">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <form method="post" action="<?php $_SERVER['REQUEST_URI'];?>">
                                                        <div class="modal-header">						
                                                            <h4 class="modal-title">Delete Banner Detail</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        </div>
                                                        <div class="modal-body">	
                                                        <input style=" visibility: hidden;" class="form-control" required name="the_ID" value="<?php echo $posts->ID;?>" readonly>				
                                                            <p>Are you sure you want to delete these Records?</p>
                                                            <p class="text-warning"><small>This action cannot be undone.</small></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                                                            <input type="submit" class="btn btn-danger" value="Delete" name="delete">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </tr> 
                                    
                                <?php
                                $loop_val ++;
                            }
                        ?> 

                    </tbody>
                        
                </table>   
                <form method="post" action="<?php $_SERVER['REQUEST_URI'];?>">  	
                    <input type="submit" class="add-more-details" value="Add Details" name="save_from_plugin">
                </form>
                   

                <!-- Edit Modal HTML -->
                <div id="addEmployeeModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post" action="<?php $_SERVER['REQUEST_URI'];?>">
                                <div class="modal-header">						
                                    <h4 class="modal-title">Add Detail</h4> 
                                    <label style="font-size:0px;" name="the-ID">ID : <?php echo get_the_ID();?></label>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>
                                <div class="modal-body">	 
                                    <div class="form-group"> 
                                        <label>Detail ID</label>
                                        <input class="form-control" required name="the_ID" value="<?php the_ID();?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Name</label><br>
                                        <select class="spacer" style="width:100%;" name="language-select" > 
                                            <?php 
                                                $tags = get_terms([
                                                    'taxonomy'  => 'banner_language',
                                                    'hide_empty'    => false
                                                ]);  

                                                foreach($tags as $get_tag){
                                                    echo '<option class="" value="'.$get_tag->name.'">'.$get_tag->description.'</option>'; 
                                                } 
                                            ?>
									</select>
                                    </div>
                                    <div class="form-group"> 
                                        <label>Link</label>
                                        <input class="form-control" required name="language-link">
                                    </div>
                                    <div class="form-group">
                                        <label>Image Path</label>
                                        <textarea class="form-control" required name="image-link"></textarea>
                                    </div> 				
                                </div>
                                <div class="modal-footer">
                                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                                    <input type="submit" class="btn btn-success" value="Add Details" name="save">
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 

            <?php
        }
?>