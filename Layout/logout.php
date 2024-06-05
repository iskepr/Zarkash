<?php
include('actions.php');
session_unset();
session_destroy();
header("Location:../Log.php");
