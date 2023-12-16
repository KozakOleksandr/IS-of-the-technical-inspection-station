<?php 
require_once('config.php');
?>
<style>
    #uni_modal .modal-footer{
        display:none
    }
    span.select2-selection.select2-selection--single,span.select2-selection.select2-selection--multiple {
    padding: 0.25rem 0.5rem;
    min-height: calc(1.5em + 0.5rem + 2px);
    height:auto !important;
    max-height:calc(3.5em + 0.5rem + 2px);
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0;
}
</style>
<div class="container-fluid">
    <form action="" id="request_form">
        <input type="hidden" name="id">
    <div class="col-12">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="category_id" class="control-label">Марка автомобіля</label>
                    <select name="category_id" id="category_id" class="form-select form-select-sm select2 rounded-0" required>
                        <option disabled selected></option>
                        <?php 
                        $category = $conn->query("SELECT * FROM `categories` where status = 1 order by category asc");
                        while($row = $category->fetch_assoc()):
                        ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo  $row['category'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="owner_name" class="control-label">Повне ім'я власнака</label>
                    <input type="text" name="owner_name" id="owner_name" class="form-control form-control-sm rounded-0" required>
                </div>
                <div class="form-group">
                    <label for="contact" class="control-label">Контактні дані власника</label>
                    <input type="text" name="contact" id="contact" class="form-control form-control-sm rounded-0" required>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">Email власника</label>
                    <input type="email" name="email" id="email" class="form-control form-control-sm rounded-0" required>
                </div>
                <div class="form-group">
                    <label for="address" class="control-label">Адреса</label>
                    <textarea rows="3" name="address" id="address" class="form-control form-control-sm rounded-0" style="resize:none" required></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="vehicle_name" class="control-label">ВІН-код автомобіля</label>
                    <input type="text" name="vehicle_name" id="vehicle_name" class="form-control form-control-sm rounded-0" required>
                </div>
                <div class="form-group">
                    <label for="vehicle_registration_number" class="control-label">Реєстраційний номер транспортного засобу</label>
                    <input type="text" name="vehicle_registration_number" id="vehicle_registration_number" class="form-control form-control-sm rounded-0" required>
                </div>
                <div class="form-group">
                    <label for="vehicle_model" class="control-label">Модель автомобіля</label>
                    <input type="text" name="vehicle_model" id="vehicle_model" class="form-control form-control-sm rounded-0" required>
                </div>
                <div class="form-group">
                    <label for="service_id" class="control-label">Послуги</label>
                    <select name="service_id[]" id="service_id" class="form-select form-select-sm select2 rounded-0" multiple required>
                        <option disabled></option>
                        <?php 
                        $service = $conn->query("SELECT * FROM `service_list` where status = 1 order by `service` asc");
                        while($row = $service->fetch_assoc()):
                        ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo  $row['service'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="service_type" class="control-label">Тип запису</label>
                    <select name="service_type" id="service_type" class="form-select form-select-sm select2 rounded-0" required>
                        <option>Клієнт доставить сам</option>
                        <option>Забрати автомобіль</option>
                    </select>
                </div>
                <div class="form-group" style="display:none">
                    <label for="pickup_address" class="control-label">Адреса розташування авто</label>
                    <textarea rows="3" name="pickup_address" id="pickup_address" class="form-control form-control-sm rounded-0" style="resize:none" required></textarea>
                </div>
            </div>
        </div>
    </div>
        <div class="w-100 d-flex justify-content-end mx-2">
            <div class="col-auto">
                <button class="btn btn-primary btn-sm rounded-0">Надіслати запис на сервіс</button>
                <button class="btn btn-dark btn-sm rounded-0" type="button" data-dismiss="modal">Закрити</button>
            </div>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('.select2').select2({
            placeholder:"Please Select Here",
            dropdownParent: $('#uni_modal')
        })
        $('#service_type').change(function(){
            var type = $(this).val().toLowerCase()
            if(type == 'pick up'){
                $('#pickup_address').parent().show()
                $('#pickup_address').attr('required',true)
            }else{
                $('#pickup_address').parent().hide()
                $('#pickup_address').attr('required',false)
            }
                
        })
        $('#request_form').submit(function(e){
            e.preventDefault()
            start_loader();
            $.ajax({
                url:'classes/Master.php?f=save_request',
                method:'POST',
                data:$(this).serialize(),
                dataType:'json',
                error:err=>{
                    console.log(err)
                    alert_toast("An error occured",'error');
                    end_loader()
                },
                success:function(resp){
                    end_loader()
                    if(resp.status == 'success'){
                        $('#uni_modal').on('hidden.bs.modal', function(){
                            if($(this).find('#request_form').length > 0){
                                setTimeout(() => {
                                    uni_modal("","success_msg.php")
                                }, 200);
                            }
                        })
                        $('#uni_modal').modal('hide')
                    }else{
                        alert_toast("An error occured",'error');
                    }
                }
            })
        })
    })
</script>