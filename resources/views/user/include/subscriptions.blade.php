<ul class="nav nav-tabs" id="myTab" role="tablist">
    @foreach ($services as $key => $item)
        @php
            $serv = $item['service'];
        @endphp
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="{{ $serv->slug }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $serv->slug }}"
                type="button" role="tab" aria-controls="{{ $serv->slug }}"
                aria-selected="true">{{ $serv->name }}</button>
        </li>
    @endforeach


</ul>
<div class="tab-content" id="myTabContent">

    @foreach ($services as $item)
        @php
            $videos = $item['service']->videos;

            if ($videos->count() > 0) {
                if ($item['package']->uploaded == 1) {
                    $videos = $videos->where('created_at', '>=', $item['package']->created_at);
                } elseif ($item['package']->uploaded == 2) {
                    $videos = $videos->where('created_at', '<=', $item['package']->created_at);
                } else {
                    $videos = $videos;
                }
            }
        @endphp

        @if ($videos->count() > 0 || $item['service']->pateron_videos->count() > 0)
            <div class="tab-pane fade" id="{{ $item['service']->slug }}" role="tabpanel"
                aria-labelledby="{{ $item['service']->slug }}-tab">
                <div class="content">
                    <ul class="nav nav-pills mb-3 innertabs" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-video-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-video" type="button" role="tab" aria-controls="pills-video"
                                aria-selected="true">Videos</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-post-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-post" type="button" role="tab" aria-controls="pills-post"
                                aria-selected="false">Pateron Post</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-video" role="tabpanel"
                            aria-labelledby="pills-video-tab">
                            <div class="table-responsive">

                                <table class="table services-videos">

                                    <tr>
                                        <th>Name</th>
                                        <th>Video</th>
                                        <th>Uploaded</th>
                                    </tr>
                                    @foreach ($videos as $inneritem)
                                        <tr>
                                            <td> {{ $inneritem->name }}</td>
                                            <td><a href="{{ asset($inneritem->video) }}" target="_blank">Watch Now</a>
                                            </td>
                                            <td>{{ Carbon\Carbon::parse($inneritem->created_at)->format('d-M-Y h:i A') }}
                                            </td>


                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-post" role="tabpanel" aria-labelledby="pills-post-tab">
                            <div class="table-responsive">
                                <table class="table services-videos">
                                    <tr>
                                        <th>Name</th>
                                        <th>Content</th>
                                        <th>Link</th>
                                    </tr>
                                    @foreach ($item['service']->pateron_videos as $patvid)
                                    <tr>
                                        <td>{{  $patvid->name }}</td>
                                        <td>{!!  $patvid->content !!}</td>
                                        <td><a href="{{ 'https://www.patreon.com' . $patvid->post_url }}" target="_blank">Go to post</a></td>
                                    </tr>

                                    @endforeach

                                </table>
                            </div>

                        </div>
                    </div>


                </div>



            </div>
        @else
            <div class="tab-pane fade" id="{{ $item['service']->slug }}" role="tabpanel"
                aria-labelledby="{{ $item['service']->slug }}-tab">
                <p class="novideos">No videos</p>
            </div>
        @endif
    @endforeach


</div>

@push('css')
    <style>
        li.nav-item button {
            color: red;
        }

        li.nav-item button:hover {
            color: red;
        }

        table.table.services-videos {
            color: white;
        }

        .table-responsive a {
            color: red;
        }

        p.novideos {
            color: white;
            margin-top: 10px;
            text-align: center;
        }
    </style>
@endpush
