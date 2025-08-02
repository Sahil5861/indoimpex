<?php 
    $categories = \App\Models\PPCategory::all();
    $colors = \App\Models\NonWovenCategory::all();
    $pp_categorise = \App\Models\PPWovenCategory::all();
?>
<!-- Party Popover -->
<div class="modal fade modal-centered" id="party_name_popup" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 1000px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enter Party Name</h5>
        <button type="button" class="btn-close close-popover" data-bs-dismiss="modal"></button>
      </div>
      <form class="popover-form" data-action="{{route('addpartymaster')}}" id="popover-party-form">

        <div class="modal-body">
          <label class="form-label">Enter Party Name</label>
          <input type="text" class="form-control popover-input" name="party_name" required>
        </div>
        <div class="modal-footer">
          <button type="submit" form="popover-party-form" class="btn btn-success">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Bopp Popover -->
<div class="modal fade modal-centered" id="bopp_popup" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 1000px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Bopp</h5>
        <button type="button" class="btn-close close-popover" data-bs-dismiss="modal"></button>
      </div>
      <form class="popover-form" data-action="{{route('addboppItemmaster')}}" id="popover-bopp-form">        
        <div class="modal-body">            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Item Code :</label>
                        <input type="text" id="item_code" class="form-control popover-input" name="item_code" required placeholder="Enter Item Name" oninput="this.value = this.value.toUpperCase();" onblur="checkIsBoppExist();">
                        <small class="bopp-error text-danger"></small>
                    </div>
                </div>              
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Item Size :</label>
                        <input type="text" id="item_size" class="form-control popover-input" name="item_size" required placeholder="Enter Item Size" oninput="this.value = this.value.toUpperCase();" >
                    </div>
                </div>  
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Item Category :</label>
                        <select name="item_category" id="item_category" class="form-control">
                          @foreach ($categories as $item)
                              <option value="{{$item->category_value}}">{{$item->category_name}}</option>
                          @endforeach
                        </select>
                    </div>
                </div>  

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Item Micron :</label>
                        <input type="text" id="item_micron" class="form-control popover-input" name="item_micron" required placeholder="Enter Item GMS" oninput="this.value = this.value.toUpperCase();" >
                    </div>
                </div>              
            </div>            
        </div>
        <div class="modal-footer">            
            <button type="submit" form="popover-bopp-form" class="btn btn-success float-right text-right">Add</button>
        </div>
    </form>
    </div>
  </div>
</div>

<!-- Metal Popover -->
<div class="modal fade modal-centered" id="metal_popup" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 1000px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Bopp Metal</h5>
        <button type="button" class="btn-close close-popover" data-bs-dismiss="modal"></button>
      </div>
      <form class="popover-form" data-action="{{route('addboppItemmaster')}}" id="popover-metal-form">        
        <div class="modal-body">            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Item Code :</label>
                        <input type="text" id="item_metal_code" class="form-control popover-input" name="item_code" required placeholder="Enter Item Name" oninput="this.value = this.value.toUpperCase();" >
                        <small class="fabric-error text-danger"></small>
                    </div>
                </div>                
            </div>            
        </div>
        <div class="modal-footer">            
            <button type="submit" form="popover-metal-form" class="btn btn-success float-right text-right">Add</button>
        </div>
    </form>
    </div>
  </div>
</div>

<!-- Fabric Popover -->
<div class="modal fade modal-centered" id="fabric_popup" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 1000px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Fabric</h5>
        <button type="button" class="btn-close close-popover" data-bs-dismiss="modal"></button>
      </div>
      <form class="popover-form" data-action="{{route('addfabricItemmaster')}}" id="popover-fabric-form">        
        <div class="modal-body">            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Item Code :</label>
                        <input type="text" id="item_code_fibre" class="form-control popover-input" name="item_code" required placeholder="Enter Item Name" oninput="this.value = this.value.toUpperCase();" onblur="checkIsFabricExist();">
                    </div>
                </div>
                <div class="col-md-6">                  
                    <div class="form-group">
                        <label>Item size :</label>
                        <input type="text" id="item_size_fibre" class="form-control popover-input" name="item_size_fibre" required placeholder="Enter Item Size" oninput="this.value = this.value.toUpperCase();" >
                    </div>
                </div>                
                <div class="col-md-6">                    
                    <div class="form-group">
                        <label>Item Color :</label>
                        <select name="item_color_fibre" id="item_color_fibre" class="form-control">
                          @foreach ($colors as $color)
                              <option value="{{$color->category_value}}">{{$color->category_name}}</option>
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">                  
                    <div class="form-group">
                        <label>Item GMS :</label>
                        <input type="text" id="item_gms_fibre" class="form-control popover-input" name="item_gms_fibre" required placeholder="Enter Item Name" oninput="this.value = this.value.toUpperCase();" >
                    </div>
                </div>                
            </div>            
        </div>
        <div class="modal-footer">            
            <button type="submit" form="popover-fabric-form" class="btn btn-success float-right text-right">Add</button>
        </div>
    </form>
    </div>
  </div>
</div>


<!-- Fabric PP Popover -->
<div class="modal fade modal-centered" id="fabric_pp_popup" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 1000px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add PP Woven</h5>
        <button type="button" class="btn-close close-popover" data-bs-dismiss="modal"></button>
      </div>
      <form class="popover-form" data-action="{{route('addfabricppItemmaster')}}" id="popover-fabric-pp-form">        
        <div class="modal-body">            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Item Code :</label>
                        <input type="text" id="item_code_fibre_pp" class="form-control popover-input" name="item_code" required placeholder="Enter Item Code" oninput="this.value = this.value.toUpperCase();" onblur="checkIsFabricPPExist();">
                        <small class="fabric-pp-error text-danger"></small>
                    </div>
                </div>              
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Item Size :</label>
                        <input type="text" id="item_size_fibre_pp" class="form-control popover-input" name="item_size_fibre_pp" required placeholder="Enter Item Size" oninput="this.value = this.value.toUpperCase();" >
                    </div>
                </div>              
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Item Category :</label>
                        <select name="item_category_fibre_pp" id="item_category_fibre_pp" class="form-control">
                          @foreach($pp_categorise as $category)
                          <option value="{{$category->category_value}}">{{$category->category_name}}</option>
                          @endforeach
                        </select>
                    </div>
                </div>              
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Item GMS :</label>
                        <input type="text" id="item_gms_fibre_pp" class="form-control popover-input" name="item_gms_fibre_pp" required placeholder="Enter Item GMS" oninput="this.value = this.value.toUpperCase();">
                    </div>
                </div>                
            </div>            
        </div>
        <div class="modal-footer">            
            <button type="submit" form="popover-fabric-pp-form" class="btn btn-success float-right text-right">Add</button>
        </div>
    </form>
    </div>
  </div>
</div>

<script>
  const mainModalEl = document.getElementById('users');
  const mainModal = new bootstrap.Modal(mainModalEl);
  let currentInput = null;
  let activePopover = null;
  let isPopoverOpen = false;
  let inputId = '';  


  // F3 key to open correct popover
  $(document).on('keydown', function (e) {
    if (e.key === 'F3') {
        e.preventDefault();
        const active = document.activeElement;
        if (active && active.classList.contains('add-data')) {
            currentInput = active;
            const popoverId = active.getAttribute('data-popover');                      
            const modalEl = document.getElementById(popoverId);
            inputId = active.getAttribute('data-id');              
            if (!modalEl) return;

            // Close previous
            if (isPopoverOpen && activePopover) {
            activePopover.hide();
            }

            activePopover = new bootstrap.Modal(modalEl, {
            keyboard: false,
            backdrop: 'static'
            });

            // Show modal first, then focus input once shown
            activePopover.show();
            isPopoverOpen = true;

            // Use Bootstrap modal's 'shown.bs.modal' event to focus after animation
            $(modalEl).off('shown.bs.modal').on('shown.bs.modal', function () {
            const firstInput = $(this).find('input:visible:first');
            firstInput.val().focus();
            });
        }
    }


    // ESC to close
    if (e.key === 'Escape') {
        
      if (isPopoverOpen && activePopover) {
        activePopover.hide();
        isPopoverOpen = false;
      } else if (mainModalEl.classList.contains('show')) {
        // mainModal.hide();
            $('#users').modal('hide');
      }
    }
  });

  // Focus input on show
  $('.modal').on('shown.bs.modal', function () {
    $(this).find('.popover-input').trigger('focus');
  });

  // AJAX submit for each popover-form
  // AJAX submit for each popover-form
    $('.popover-form').on('submit', function (e) {
        e.preventDefault();

        const $form = $(this);
        const modal = $form.closest('.modal');
        const actionUrl = $form.data('action');        
        const formEl = $form[0]; // Get the raw DOM form element
        // Collect all form data
        const formData = new FormData(formEl);

        // Update the original input (if needed)
        const inputVal = modal.find('.popover-input').val();

        console.log(currentInput);


        
        if (currentInput) {
            currentInput.value = inputVal;
            currentInput.focus();
        }

        let msg_box = $(currentInput).closest('.input-group').nextAll('.response-msg:first');                

        

        // Submit using AJAX
        if (actionUrl) {
            for (const [key, value] of formData.entries()) {
                console.log(key, value);
            }
            
            
            const instance = bootstrap.Modal.getInstance(modal[0]);
            instance.hide();

            console.log(actionUrl);

            
            
            
            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from processing data
                contentType: false, // Let the browser set content type (multipart/form-data)
                success: function (response) {
                    console.log('Submitted successfully:', response);
                    
                    msg_box.text(response.message);

                    setTimeout(() => {
                      msg_box.text('');
                    }, 3000);
                },
                error: function (xhr) {
                    console.error('Error submitting:', xhr);
                },
                complete: function () {
                    const instance = bootstrap.Modal.getInstance(modal[0]);
                    instance.hide();
                    isPopoverOpen = false;                    
                  
                    if (inputId == 'bopp_code') {
                      setTimeout(() => {
                        updateBoppcodes();
                      }, 200);
                    }

                    if (inputId == 'metal_code') {
                      setTimeout(() => {
                        updateMetalcodes();
                      }, 200);
                    }

                    if (inputId == 'fabric_pp_code') {
                      setTimeout(() => {
                        updateFibrePPcodes();
                      }, 200);
                    }

                    if (inputId == 'fabric_code') {
                      setTimeout(() => {
                        updateFibrecodes();
                      }, 200);
                    }
                }
            });
        } else {
            const instance = bootstrap.Modal.getInstance(modal[0]);
            instance.hide();
            isPopoverOpen = false;
        }
    });


  // Manual close
  $('.close-popover').on('click', function () {
    const modal = $(this).closest('.modal')[0];
    const instance = bootstrap.Modal.getInstance(modal);
    if (instance) {
      instance.hide();
      isPopoverOpen = false;
    }
  });

  // Reset flags
  $('.modal').on('hidden.bs.modal', function () {
    isPopoverOpen = false;
    activePopover = null;
  });
</script>
<script>
  function checkIsBoppExist(){
    let value = $('#item_code').val();
    $.ajax({
      type: "GET",
      url: "{{route('check-bopp')}}",
      data: {
        value : value
      },
      success: function(response){
        console.log(response);  
        if (response.status == false) {
          $('.bopp-error').html(response.message);
          $('#item_size').val('');
          $('#item_category').val('');
          $('#item_micron').val('');
        }      
        else if(response.status == true){
          let size = response.size;
          let category = response.category;
          let micron = response.micron;

          $('.bopp-error').html('');
          $('#item_size').val(size);
          $('#item_category').val(category);
          $('#item_micron').val(micron);
        }
      }
    });

  }

  function checkIsFabricExist(){
    let value = $('#item_code_fibre').val();
    $.ajax({
      type: "GET",
      url: "{{route('check-fabric')}}",
      data: {
        value : value
      },
      success: function(response){
        console.log(response);  
        if (response.status == false) {
          $('.fabric-error').html(response.message);
          $('#item_size_fibre').val('');
          $('#item_color_fibre').val('');
          $('#item_gms_fibre').val('');
        }      
        else if(response.status == true){
          let size = response.size;
          let category = response.category;
          let micron = response.micron;

          $('.fabric-error').html('');
          $('#item_size_fibre').val(response.size);
          $('#item_color_fibre').val(response.color);
          $('#item_gms_fibre').val(response.gsm);
        }
      }
    });

  }

  function checkIsFabricPPExist(){
    let value = $('#item_code_fibre_pp').val();
    $.ajax({
      type: "GET",
      url: "{{route('check-fabric-pp')}}",
      data: {
        value : value
      },
      success: function(response){
        console.log(response);  
        if (response.status == false) {
          $('.fabric-pp-error').html(response.message);
          $('#item_size_fibre_pp').val('');
          $('#item_category_fibre_pp').val('');
          $('#item_gms_fibre_pp').val('');
        }      
        else if(response.status == true){
          let size = response.size;
          let category = response.category;
          let micron = response.micron;

          $('.fabric-pp-error').html('');
          $('#item_size_fibre_pp').val(response.pp_size);
          $('#item_category_fibre_pp').val(response.pp_category);
          $('#item_gms_fibre_pp').val(response.pp_gms);
        }
      }
    });

  }
</script>