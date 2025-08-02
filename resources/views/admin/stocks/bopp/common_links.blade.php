<div class="card-tools text-end" style="display: flex; align-items:center; justify-content: space-between;">
    <div class="btns">                                
        <a href="#" class="text-white btn btn-primary-2" data-toggle="modal" data-target="#bopp"> + Add </a>                                
        <a href="#" class="text-white btn btn-primary-2" data-toggle="modal" data-target="#bopp_issue"> + Add Issue</a>                                
        {{-- <button class="btn btn-danger" id="delete-selected">Delete Selected</button>                                                          --}}

        <a href="{{route('admin.material-stock.bopp-received')}}" class="btn btn-teal txt-white">Received Bopp</a>
        <a href="{{route('admin.material-stock.bopp-issued')}}" class="btn btn-warning txt-white">Issued Bopp</a>
    </div>                            
</div>


<div id="bopp" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="boppLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="boppLabel">ID: BOPP1010101 | Date: {{ now()->format('d-m-Y') }}</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{route('material-stock.bopp-save')}}" method="post">
                    @csrf
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>

                        <div class="row">
                            <div class="col-md-4">
                                <h4 class="modal-title">BOPP</h4>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Item Code:</label>
                                    <input type="text" id="bopp_item_code" class="form-control bopp-item bopp_item_code" autocomplete="off" style="text-transform: uppercase" name="bopp[item_code]">
                                    <div class="bopp-item-suggestions suggestions" style="display: none;"></div>                                    
                                    <div class="all-tags"></div>
                                    <div id="email_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date:</label>
                                    <input type="text" name="bopp[submit_date]" class="form-control" value="{{ now()->format('d-m-Y') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-seperator-dashed"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Size:</label>
                                    <input type="text" id="size" class="form-control bopp_size" name="bopp[bopp_size]" required placeholder="Enter Size" readonly>
                                </div>
                            </div>

                            <?php
                                $boppcategories = [
                                    'G' => 'GLOSS',
                                    'M' => 'MATT',
                                    'ME' => 'METALLISE',
                                    'WCME' => 'W C METALLISE'                                   
                                ];
                            ?>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>BOPP Category:</label>
                                    <select class="form-control bopp-cat" name="bopp[bopp_category]" required readonly>
                                        <option value="">--Select--</option>
                                        @foreach ($boppcategories as $key => $item)
                                            <option value="{{$key}}">{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Micron:</label>
                                    <input type="text" id="micron" class="form-control bopp_micron" name="bopp[bopp_micron]" required placeholder="Enter Micron" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Party Name:</label>
                                    <input type="text" id="party_name" class="form-control" name="bopp[bopp_party_name]" required placeholder="Enter Party Name">
                                    <script>
                                        document.getElementById('party_name').addEventListener('blur', function () {
                                            let words = this.value.toLowerCase().split(' ');
                                            for (let i = 0; i < words.length; i++) {
                                                if (words[i].length > 0) {
                                                    words[i] = words[i][0].toUpperCase() + words[i].substr(1);
                                                }
                                            }
                                            this.value = words.join(' ');
                                        });
                                    </script>
                                    <div class="party_name_suggestions suggestions" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-seperator-dashed"></div>

                        <div id="paramsWrapper">
                            <div class="row param-row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Roll Number:</label>
                                        <input type="text" name="roll-number[]" autocomplete="off" class="form-control" placeholder="Enter Roll Number">
                                    </div>
                                </div>
    
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Weight:</label>
                                        <div class="input-group">
                                            <input type="text" name="weights[]" autocomplete="off" class="form-control" placeholder="Enter Weight">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Kg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Remarks (optional):</label>
                                        <input type="text" name="remarks[]" autocomplete="off" class="form-control" placeholder="Enter Remarks">
                                    </div>
                                </div>
    
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Add More:</label><br>
                                        <button type="button" class="btn add_bopp btn-sm btn-success btn-circle btn-shadow w-100">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-seperator-dashed"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-left" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success-2 float-right text-right add_bp">Submit & Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="bopp_issue" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="boppLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="boppLabel">ID: BOPP1010101 | Date: {{ now()->format('d-m-Y') }}</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{route('material-stock.boppissue-save')}}" method="post">
                    @csrf
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>

                        <div class="row">
                            <div class="col-md-4">
                                <h4 class="modal-title">BOPP Issue</h4>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Item Code:</label>
                                    <input type="text" id="bopp_item_code_issue" class="form-control bopp-item bopp_item_code_issue" autocomplete="off" style="text-transform: uppercase" name="issue[item_code]">
                                    <div class="bopp-item-suggestions suggestions" style="display: none;"></div>                                    
                                    <small class="text-danger item-coed-issue-error"></small>
                                    <div class="all-tags"></div>
                                    <div id="email_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date:</label>
                                    <input type="text" name="issue[submit_date]" class="form-control" value="{{ now()->format('d-m-Y') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-seperator-dashed"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Size:</label>
                                    <input type="text" id="size" class="form-control bopp_issue_size" name="issue[bopp_size]" required placeholder="Enter Size" readonly>
                                </div>
                            </div>

                            <?php
                                $boppcategories = [
                                    'G' => 'GLOSS',
                                    'M' => 'MATT',
                                    'ME' => 'METALLISE',
                                    'WCME' => 'W C METALLISE'                                   
                                ];
                            ?>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>BOPP Category:</label>
                                    <select class="form-control bopp-cat" name="issue[bopp_category]" required readonly>
                                        <option value="">--Select--</option>
                                        @foreach ($boppcategories as $key => $item)
                                            <option value="{{$key}}">{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Micron:</label>
                                    <input type="text" id="micron" class="form-control bopp_issue_micron" name="issue[bopp_micron]" required placeholder="Enter Micron" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Name:</label>
                                    <input type="text" id="job_name" class="form-control" name="issue[job_name]" required placeholder="Enter Job Name" onblur="this.value = this.value.toUpperCase()" oninput="checkIsExist(this)">                                    
                                    <small class="job_name_error text-danger job_error_message" style="display: none;"></small>                                    
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cut Wastage:</label>
                                    <input type="text" id="cut_wastage" class="form-control" name="issue[cut_wastage]" required placeholder="Cut Wastage">                                                                        
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Roll Used:</label>
                                    <input type="text" id="roll_used" class="form-control" name="issue[roll_used]" required placeholder="Roll Used">                                                                        
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="roll-table" style="display: none;">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Roll</th>
                                                <th>Weight</th>
                                                <th>Select</th>
                                            </tr>
                                        </thead>
                                        <tbody class="roll-table-body">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                                            
                        <div class="form-seperator-dashed"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-left" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success-2 float-right text-right add_bp add_issue_bopp" disabled>Submit & Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="bopp_issue_add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="boppLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="boppLabel">ID: BOPP1010101 | Date: {{ now()->format('d-m-Y') }}</h4>

                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{route('material-stock.boppissue-save')}}" method="post">
                    @csrf
                    <div class="form-body">
                        <div class="form-seperator-dashed"></div>

                        <div class="row">
                            <div class="col-md-4">
                                <h4 class="modal-title">BOPP Issue</h4>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">                                    
                                    <input type="hidden" id="bopp_roll_id" class="form-control" name="bopp_roll_id">
                                    <input type="hidden" id="bopp_item_code_issue_add" class="form-control bopp-item bopp_item_code_issue" name="issue[item_code]">                                                                        
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date:</label>
                                    <input type="text" name="issue[submit_date]" class="form-control" value="{{ now()->format('d-m-Y') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-seperator-dashed"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Size:</label>
                                    <input type="text" id="roll_size" class="form-control bopp_issue_size" name="issue[bopp_size]" required placeholder="Enter Size" readonly>
                                </div>
                            </div>
                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>BOPP Category:</label>
                                    <select class="form-control bopp-cat" id="roll_category" name="issue[bopp_category]" required readonly>                                                                                
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Micron:</label>
                                    <input type="text" id="roll_micron" class="form-control bopp_issue_micron" name="issue[bopp_micron]" required placeholder="Enter Micron" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Name:</label>
                                    <input type="text" id="job_name" class="form-control" name="issue[job_name]" required placeholder="Enter Job Name" onblur="this.value = this.value.toUpperCase()" oninput="checkIsExist(this)">                                    
                                    <small class="job_name_error text-danger job_error_message" style="display: none;"></small>                                    
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cut Wastage:</label>
                                    <input type="text" id="cut_wastage" class="form-control" name="issue[cut_wastage]" required placeholder="Cut Wastage">                                                                        
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Roll Used:</label>
                                    <input type="text" id="roll_used" class="form-control" name="issue[roll_used]" required placeholder="Roll Used">                                                                        
                                </div>
                            </div>
                        </div>
                                            
                        <div class="form-seperator-dashed"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-left close-modal" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success-2 float-right text-right add_bp">Submit & Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>