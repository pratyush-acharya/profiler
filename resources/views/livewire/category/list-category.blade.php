<div>
@include('get-alert')

    <div class="row three-labels">

                <div class="col-md-3 dash-stat1 mx-auto">
                    <div class="numbers">
                       <h1 class="csit-header">{{ $enrolled }} <p>Enrolled Students</p> </h1>
                    </div>

                </div>

                <div class="col-md-3 dash-stat2 mx-auto">
                    <div class="numbers">
                        <h1 class="csit-header">{{ $csit }} <p>Bsc. CSIT</p></h1>
                    </div>

                </div>

                <div class="col-md-3 dash-stat3 mx-auto">
                    <div class="numbers">

                        <h1 class="csit-header">{{ $bca }}<p>BCA</p></h1>

                    </div>

                </div>
            </div>

<!--            Categories-->

            <div class="row">
                <div class="container-fluid">
                    <h6>Student Categories</h6>

                    <table class="table csit-table">
                        <thead>
                        <tr>
                            <th scope="col">S.N.</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($categories as $category)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->description }}</td>
                            <td><a href="#" onclick="confirm('Do You Want To Change State?') || event.stopImmediatePropagation()" wire:click.prevent="changeStatus({{ $category->id }})"><i class="fas fa-circle fa-lg @if($category->status == 1) text-success @else text-danger @endif"></i></a></td>
                            <td>
                                <a href="edit/{{$category->id}}"><i class="far fa-edit"></i></a>
                                <form style="display: inline-block">
                                    <a href="#" onclick="confirm('Confirm Action?') || event.stopImmediatePropagation()" wire:click="deleteCategory({{ $category->id }})"><i class="far fa-trash-alt"></i></a>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

            <select class="form-control comment-option" wire:model="categoryId">
                @foreach($categoryList as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select><br>

            @if($categoryId != NULL)
            <livewire:category.cat-comment :id=$categoryId>
            @endif
</div>