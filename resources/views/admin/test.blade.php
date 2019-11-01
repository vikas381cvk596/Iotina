@extends('layouts.app')

@section('content')

  <div id="test_page" class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <p align="center">Testing View Page</p>
      <?php 
      if($results) {
        echo "Results:::".$results;
        ?>
        <input type="hidden" id="results_hidden" value='<?php echo $results;?>'>
        <?php
      } else {
        echo "Results not found";
      }
      ?>
    </div>
  </div>

@endsection