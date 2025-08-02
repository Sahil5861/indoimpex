<script>
    function editUser(element){
        var id = $(element).data('id');  
        
        $.ajax({
            url: `{{ route('getJobdetailsEdit') }}`,
            data:{
                id:id,
            },
            method: 'GET',
            success: function(response){                
                console.log(response)
                let data = response.data;
                let jobdetails = data.jobdetails;
                let party = data.party;
                let jobname = data.jobname;
                let jobtype = data.jobtype;
                let jobcylinder = data.job_cylinder;
                if (jobcylinder.length > 0) {
                    if (jobcylinder) {
                        for (const key in jobcylinder) {
                            const inputName = `cylinder[${key}]`;
                            $(`input[name="${inputName}"]`).val(jobcylinder[key]);
                        }
                    }
                }


                $('#job_date').val(jobdetails.submit_date);
                $('#job_code').val(jobdetails.job_unique_code);
                $('#party_name').val(party.party_name);
                $('#job_name').val(jobname.job_name);
                $('#job_type').val(jobtype.id);
                $('#printing_type').val(jobdetails.printing_type);
                $('.bag_select').val(jobdetails.bag_type);
                $('.total_bag_weight').val(jobdetails.bag_total_weight);
                $('.bag_pet_size').val(jobdetails.bag_pet);
                $('.bag_job_size').val(jobdetails.bag_circum);
                $('.bag_gaz_size').val(jobdetails.bag_gazette);
                $('#job_description').val(jobdetails.job_description);

                $('.printing_type').each( function (){
                    const typeBtn = $(this);                     
                    if(typeBtn.val() === jobdetails.printing_type){                        
                        typeBtn.addClass('active')
                    }
                })



                $('#users').modal('show');
                $('#users .modal-title').html('Edit Job details');            
            }
        })

        
    }
</script>