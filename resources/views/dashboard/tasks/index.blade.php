@extends('dashboard')

@section('dash-ui')
    <article class="content-area ">
        <article class="container">
            <section class="block">
                <section class="hero has-background-info mt-1 ml-1 mr-1 mb-0">
                    <nav class="level">
                        <div class="level-left">
                            <p class="panel-heading has-background-info has-text-white">
                                My Tasks/ Annotated Paragraphs
                            </p>
                        </div>

                        <div class="level-right mr-1">
                            <div class="control has-icons-left mr-2">
                                <div class="select">
                                    <select>
                                        <option disabled selected hidden>Filter by..</option>
                                        <option>All</option>
                                        <option>Labelled</option>
                                        <option>Modified</option>
                                        <option>Bypassed</option>
                                        <option>Time's Up</option>
                                        @foreach ($labels as $label)
                                            <option>{{$label['label_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="icon is-left">
                                    <i class="fa fas fa-sort"></i>
                                </div>
                            </div>


                            <div class="field has-addons">
                                <div class="control is-fluid">
                                    <input class="input" type="text" placeholder="Search by Name">
                                </div>
                                <div class="control">
                                    <a class="button is-grey">
                                        Search
                                    </a>
                                </div>
                            </div>
                        </div>
                    </nav>
                </section>
            </section>

            <article class="container is-fluid p-1">
                @foreach ($tasks as $task)
                    <!-- Paragraphs -->
                    <section class="box ">
                        <section class="block">
                            {{substr($task->content,0,81)}}
                            <span class="dots">...</span>
                            <span class="more">
                                {{substr($task->content,81)}}
                            </span>
                            <button class="read is-text has-text-link p-0">
                                <span>Read More</span>
                            </button>
                        </section>
                        <div class="columns is-vcentered">
                            <div class="column is-narrow">
                                <span class="tag is-info is-light is-medium">
                                    {{$task->status}}
                                </span>
                            </div>
                            <div class="column">
                                <div class="tags is-multiline">
                                    @if($task->label_num !== null)
                                        @foreach (explode(",",$task->label_num) as $label_num)
                                            <span class="tag is-primary is-light is-medium">
                                                {{$labels[$label_num]['label_name']}}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="column is-narrow">
                                <button class="button is-primary">
                                    <span class="icon is-small">
                                        <i class="fa fas fa-edit"></i>
                                    </span>
                                    <span>Modify</span>
                                </button>
                                <button class="button is-info has-text-white has-text-weight-bold js-modal-trigger"
                                data-target={{"modal_details-".$task->doc_id."-".$task->paragraph_num}}>
                                    <span class="icon is-small">
                                        <i class="fa fas fa-eye"></i>
                                    </span>
                                    <span>Paragraph Details</span>
                                </button>
                            </div>
                        </div>
                    </section>
                    <!-- MODAL_DETAILS -->
                    <div class="modal p-2" id={{"modal_details-".$task->doc_id."-".$task->paragraph_num}}>
                        <div class="modal-background"></div>
                        <div class="modal-card">
                            <header class="modal-card-head">
                                <p class="modal-card-title has-text-info-dark">Paragraph Details</p>
                                <button class="delete" aria-label="close"></button>
                            </header>
                            <section class="modal-card-body has-text-left">
                                <p class="subtitle has-text-info"><a href={{$task->document_link}}>Document Link</a></p>
                                    <strong>Document Number: </strong><span>{{$task->doc_id}}</span><br>
                                    <strong>Paragraph Number: </strong><span>{{$task->paragraph_num}}</span><br>
                                    <strong>Case Number: </strong><span>{{$task->case_number}}</span><br>
                                    <strong>Title: </strong><span>{{$task->title}}</span><br>
                                    <strong>Page Number: </strong><span>{{$task->page}}</span><br>
                                    <strong>Date of the Judgement: </strong><span>{{$task->date}}</span><br>
                                    <strong>Allocation time: </strong><span>{{$task->allocation_time}}</span><br>
                                    @if(strcmp($task->status,"labeled")==0)
                                        <strong>Labeled time: </strong><span>{{$task->labeled_time}}</span><br>
                                    @endif
                            </section>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination Nav -->
                <nav class="pagination is-centered" role="navigation" aria-label="pagination">
                    <a class="pagination-previous has-background-white" href={{$tasks->previousPageUrl()}}>
                        <span class="icon is-small">
                            <i class="fa fas fa-chevron-left"></i>
                        </span>
                    </a>
                    <a class="pagination-next has-background-white" href={{$tasks->nextPageUrl()}}>
                        <span class="icon is-small">
                            <i class="fa fas fa-chevron-right"></i>
                        </span>
                    </a>
                    <ul class="pagination-list">
                        <li><a class="pagination-link has-background-white" aria-label="Goto page 1" href={{$tasks->url(1)}}>1</a></li>
                        <li><span class="pagination-ellipsis">&hellip;</span></li>
                        {{-- <li><a class="pagination-link has-background-white" aria-label="Goto page 45" href={{$tasks->previousPageUrl()}}>45</a>
                        </li> --}}
                        <li><a class="pagination-link is-current" aria-label={{"Page ".$tasks->currentPage()}}
                                aria-current="page" href={{$tasks->url($tasks->currentPage())}}>{{$tasks->currentPage()}}</a></li>
                        {{-- <li><a class="pagination-link has-background-white" aria-label="Goto page 47" href={{$tasks->nextPageUrl()}}>47</a>
                        </li> --}}
                        <li><span class="pagination-ellipsis">&hellip;</span></li>
                        <li><a class="pagination-link has-background-white" aria-label={{"Goto page ".$tasks->lastPage()}} href={{$tasks->url($tasks->lastPage())}}>{{$tasks->lastPage()}}</a>
                        </li>
                    </ul>
                </nav>
            </article>
        </article>
    </article>

    <!--jQuery CDN-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="{{ mix('resources/js/readmore.js') }}"></script>
    <script src="{{ mix('resources/js/modal.js') }}"></script>
@endsection