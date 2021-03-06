@extends('layouts/default')

@section('title', "Posts")
<x-system.message />

@php
    $titleSearch = request()->get("s","");
@endphp

@section('header')
@include('partials.common.header');
@endsection

@section('content')
<div class="overflow-x-hidden bg-gray-100">
    <div class="px-6 py-8">
        <div class="container flex justify-between mx-auto">
            <div class="w-full lg:w-8/12">
                <div>
                    <form id="postSearchForm" action="{{ route('posts.index', ['s' => $titleSearch]) }}" method="GET">
                        <div class="flex mb-5">
                            <x-form.input id="titleSearch" class="w-full px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-100 focus:border-indigo-300 dark:bg-gray-700 dark:text-white dark:placeholder-gray-500 dark:border-gray-600 dark:focus:ring-gray-900 dark:focus:border-gray-500" type="text" name="s" placeholder="Search" value="{{ $titleSearch }}" />
                            <input id="btnSearch" class="bg-red-500 text-white px-6 py-2 rounded font-medium mx-3 hover:bg-red-600 transition duration-200 each-in-out cursor-pointer" type="reset" value="Reset"/>
                        </div>
                    </form>
                </div>
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-bold text-gray-700 md:text-2xl">{{ __("misc.text.post") }}</h1>
                </div>
                @foreach ($posts as $post)
                @php
                    $postPhoto = data_get($post ?? [], 'photo', '');
                    $postPictureUrl = $postPhoto ? url('/') . '/' . str_replace('public', 'storage', $postPhoto) : "https://semantic-ui.com/images/wireframe/image.png";

                    $photo = $post->user->photo;
                    $photoPictureUrl = $photo ? url("/"). '/' . str_replace("public" , "storage" , $photo) : "https://semantic-ui.com/images/wireframe/image.png";

                    $category = $post->categories->first();
                    $categoryTitle = data_get($category, "title", __("post.text.no_category"));
                    $categorySlug = data_get($category,"slug", "#");
                @endphp
                <div class="mt-6">
                    <div class="max-w-4xl px-10 py-6 mx-auto bg-white rounded-lg shadow-md">
                        <div class="">
                            <img  @if ($postPictureUrl) src="{{ $postPictureUrl }}" @endif alt="" class="img-post--">
                        </div>
                        <div class="mt-2"><a href="{{ route('posts.show', $post->id) }}" class="actClickPost text-2xl font-bold text-gray-700 hover:underline">{{ $post->title }}</a>
                            <p class="mt-2 text-gray-600">{!! $post->content !!}</p>
                        </div>
                        <div class="flex items-center justify-between mt-4"><a href="{{ route('posts.show', $post->id) }}"
                                class="text-blue-500 hover:underline">{{ __("misc.text.readMore") }}</a>
                            <div><a href="{{route('users.show', $post->user->id)  }}" class="flex items-center"><img
                                    @if ($photoPictureUrl) src="{{ $photoPictureUrl }}" @endif
                                        alt="avatar" class="hidden object-cover w-10 h-10 mx-4 rounded-full sm:block">
                                    <h1 class="font-bold text-gray-700 hover:underline">{{ $post->user->name }}</h1>
                                    <a href="{{ route('posts.index',['cat' => $categorySlug]) }}"><span class="leftArrowBox  bg-gray-700 p-1 text-white relative border-black border-2 rounded-md inline-block m-4 whitespace-no-wrap">{{ $categoryTitle }}</span></a>
                                    <div class="">

                                    </div>
                                </a></div>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="pagination--">
                    {{ $posts->appends(request()->except('page'))->links() }}
                </div>
            </div>
            <div class="hidden w-4/12 -mx-8 lg:block">
                <div class="px-8 mt-10">
                    <h1 class="mb-4 text-xl font-bold text-gray-700">Categories</h1>
                    <div class="flex flex-col max-w-sm px-4 py-6 mx-auto bg-white rounded-lg shadow-md">
                        <ul>
                            @forelse ($categories as $category)
                            <li>
                                <a href="{{ route('posts.index',['cat' => $category->slug]) }}" class="mx-1 font-bold text-gray-700 hover:text-gray-600 hover:underline">-
                                    {{ $category->title }}
                                </a>
                            </li>
                            @empty
                            <li>
                                <a href="#" class="mx-1 font-bold text-gray-700 hover:text-gray-600 hover:underline">-
                                    {{ __("category.alert.no-category") }}
                                </a>
                            </li>
                            @endforelse

                        </ul>
                    </div>
                </div>
                <div class="px-8 mt-10">
                    <div class="flex flex-col max-w-sm px-4 py-6 mx-auto bg-white rounded-lg shadow-md">
                        <a href="{{ route('posts.create') }}" class="mx-1 font-bold text-gray-700 hover:text-gray-600 hover:underline">+
                            {{ __("misc.button.create_post") }}</a>
                    </div>
                </div>
                <!--<div class="px-8 mt-10">
                    <h1 class="mb-4 text-xl font-bold text-gray-700">Recent Post</h1>
                    <div class="flex flex-col max-w-sm px-8 py-6 mx-auto bg-white rounded-lg shadow-md">
                        <div class="flex items-center justify-center"><a href="#"
                                class="px-2 py-1 text-sm text-green-100 bg-gray-600 rounded hover:bg-gray-500">Laravel</a>
                        </div>
                        <div class="mt-4"><a href="#" class="text-lg font-medium text-gray-700 hover:underline">Build
                                Your New Idea with Laravel Freamwork.</a></div>
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center"><img
                                    src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=731&amp;q=80"
                                    alt="avatar" class="object-cover w-8 h-8 rounded-full"><a href="#"
                                    class="mx-3 text-sm text-gray-700 hover:underline">Alex John</a></div><span
                                class="text-sm font-light text-gray-600">Jun 1, 2020</span>
                        </div>
                    </div>
                </div>-->
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('btnSearch').addEventListener('click', function() {
        document.getElementById('titleSearch').value = '';
        document.getElementById('postSearchForm').submit();
    });
</script>
@endsection
