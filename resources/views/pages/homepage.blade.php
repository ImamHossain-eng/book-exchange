@extends('layouts.home')
@section('content')

<head>

</head>
<body>
  <!--header section start -->
  @include('parts.navbar')
<!-- header section end -->
<!-- banner section start -->
  @include('parts.banner')
<!-- banner section end -->
<!-- about section start -->
  @include('parts.about')
<!-- about section end -->
<!-- our service section start -->
  @include('parts.our_service')
<!-- our service section end -->
<!-- project section start -->
  @include('parts.project_section')
<!-- project section end -->   
<!-- our price section start -->   
  @include('parts.our_price')
<!-- our price section end -->   

<!-- contact section start -->
  @include('parts.contact')
<!-- contact section end -->
<!-- footer section start -->
  @include('parts.footer')
<!-- footer section end -->







</body>







@endsection