<?php 
   include_once('config.php');
     session_start();
     if(isset($_POST['submit'])){
		 $username  = strip_tags($_POST['username']);
		 $email     =  $_POST['email'];
		 $gender    =  $_POST['gender'];
		 $about     = strip_tags($_POST['about']);
		 $facebook  =  htmlspecialchars($_POST['facebook']);
		 $twitter   =   htmlspecialchars($_POST['twitter']);
		 $youtube   =   htmlspecialchars($_POST['youtube']);
		 $date      =  date("Y-m-d");
		 
		 if(empty($username)){
			 echo '<div class="alert alert-danger" role="alert"> الرجاء إدخال اسم المستخدم</div>';
		 }elseif(empty($email)){
			 echo '<div class="alert alert-danger" role="alert"> الرجاء أدخل بريدك الالكتروني</div>';
		  }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			 echo '<div class="alert alert-danger" role="alert"> الرجاء إدخال بريد إلكتروني صحيح</div>';
		 }elseif(empty($_POST['password'])){
			 echo '<div class="alert alert-danger" role="alert"> أدخل كلمة المرور</div>';
		 }elseif(empty($_POST['pass'])){
			 echo '<div class="alert alert-danger" role="alert"> تأكيد كلمة المرور</div>';
		 }elseif ($_POST['password']!= $_POST['pass']){
			 echo '<div class="alert alert-danger" role="alert"> كلمة المرور غير متطابقة</div>';
		 }else{
             $mysql_username = mysqli_query($conn, "SELECT `username` FROM `users` WHERE `username` = '$username'");
			 $mysql_email    = mysqli_query($conn, "SELECT `email` FROM `users` WHERE `email` = '$email'");
			 if(mysqli_num_rows($mysql_username) > 0){
				 echo '<div class="alert alert-danger" role="alert">عذرا, و لكن اسم المستخدم مسجل بالفعل!</div>';
			   }elseif(mysqli_num_rows($mysql_email) > 0){ 
			     echo '<div class="alert alert-danger" role="alert">عذرا, ولكن البريد الالكتروني مسجل بالفعل!</div>'; 	 
			  }else{ 
			  
			  if(isset($_FILES['image'])){
				   $image =   $_FILES['image'];
				   $image_name =   $image['name'];
				   $image_tmp  =   $image['tmp_name'];
				   $image_size  =  $image['size'];
				   $image_error =  $image['error'];
				   
				   $image_exe = explode('.' , $image_name);
				   $image_exe = strtolower(end($image_exe));
				   
				   $allowd = array('gif','png','jpg','jpeg');
				    if(in_array($image_exe , $allowd)){
						 if($image_error === 0){
							 if($image_size <= 3000000){
							$new_name = uniqid('user',false) . '.' . $image_exe;
							$image_dir = '../images/avatar/'. $new_name;
							$image_db = 'images/avatar/'. $new_name;
									if(move_uploaded_file($image_tmp , $image_dir)){	
										$password =  md5($_POST['password']) ;
										$insert = "INSERT INTO `users`(`username` ,
																		 `email` ,
																		 `password` ,
																		 `gender` ,
																		 `avatar` , 
																		 `about_user` ,
																		 `facebook`, 
																		 `twitter`,
																		 `youtube`, 
																		 `reg_date` ,
																		 `role`)
																		 VALUES
																		('$username',
																		 '$email',
																		 '$password',
																		 '$gender',
																		 '$image_db',
																		 '$about',
																		 '$facebook',
																		 '$twitter',
																		 '$youtube',
																		 '$date',
																		 'user'
																		   )";
										$insert_sql = mysqli_query($conn , $insert);	
										 if(isset($insert_sql)){
											 if(isset($insert_sql)){
											 $user_info = mysqli_query($conn, "SELECT * FROM `users` WHERE `username` = '$username'");
											 $user = mysqli_fetch_assoc($user_info);
											   $_SESSION['id'] = $user['user_id'];
											   $_SESSION['user'] = $user['username'];
											   $_SESSION['email'] = $user['email'];
											   $_SESSION['gender'] = $user['gender'];
											   $_SESSION['avatar'] = $user['avatar'];
											   $_SESSION['about'] = $user['about_user'];
											   $_SESSION['facebook'] = $user['facebook'];
											   $_SESSION['twitter'] = $user['twitter'];
											   $_SESSION['youtube'] = $user['youtube'];
											   $_SESSION['date'] = $user['reg_date'];
											   $_SESSION['role'] = $user['role'];
												echo '<div class="alert alert-success" role="alert">تم تسجيلك لدينا بنجاح ..جاري تحويلك إلى الصفحة الرئيسية</div>';
											   echo  '<meta http-equiv=refresh content="3; \'index.php\' "/>';
				                             } 
										 }
									}else{
										echo '<div class="alert alert-danger" role="alert">عذرا حدث خطـأ أثناء نقل الملف</div>';
									}
				  }else{
							 echo '<div class="alert alert-danger" role="alert"> عذرا و لكن حجم الصورة كبير جدا .يجب أن لا يتعدى 2 MG</div>'; 
					 }	 
				  }else{ 
					   echo '<div class="alert alert-danger" role="alert"> عذرا حدث خطـأ غير متوقع أثناء تحميل الصورة</div>';    
					 }	 
				  }else{ 
					   echo '<div class="alert alert-danger" role="alert"> الرجاء إدخال صورة صحيحة</div>';	
					}
			  }else{
			    $password =  md5($_POST['password']) ;
				$insert = "INSERT INTO `users`(`username` ,
														 `email` ,
														 `password` ,
														 `gender` ,
														 `avatar` , 
														 `about_user` ,
														 `facebook`, 
														 `twitter`,
														 `youtube`, 
														 `reg_date` ,
														 `role`)
														 VALUES
														('$username',
														 '$email',
														 '$password',
														 '$gender',
														 'images/non-avatar.png',
														 '$about',
														 '$facebook',
														 '$twitter',
														 '$youtube',
														 '$date',
														 'user'
														   )";
						$insert_sql = mysqli_query($conn , $insert);	
						 if(isset($insert_sql)){
						 $user_info = mysqli_query($conn, "SELECT * FROM `users` WHERE `username` = '$username'");
						 $user = mysqli_fetch_assoc($user_info);
						   $_SESSION['id'] = $user['user_id'];
						   $_SESSION['user'] = $user['username'];
						   $_SESSION['email'] = $user['email'];
						   $_SESSION['gender'] = $user['gender'];
						   $_SESSION['avatar'] = $user['avatar'];
						   $_SESSION['about'] = $user['about_user'];
						   $_SESSION['facebook'] = $user['facebook'];
						   $_SESSION['twitter'] = $user['twitter'];
						   $_SESSION['youtube'] = $user['youtube'];
						   $_SESSION['date'] = $user['reg_date'];
						   $_SESSION['role'] = $user['role'];
						    echo '<div class="alert alert-success" role="alert">تم تسجيلك لدينا بنجاح ..جاري تحويلك إلى الصفحة الرئيسية</div>';
						   echo  '<meta http-equiv=refresh content="3; \'index.php\' "/>';
				        } 
			   }
			 } 
		   }
		 } 
?>