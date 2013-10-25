<script>
        function f() {
            var obj = document.getElementById("Bucket");
            status = obj.value;
            
          

            $.ajax({url: "<?php echo Yii::app()->createUrl('site/ajaxcall'); ?>",
                data: {id:status, value:status},
                type: 'post',
                success: function(output) {
                 
                }
            });
        

        }






    </script>
    
    
    
      <form >
        <h2>Set your Status </h2>
        <br/> <br/>
        <label title="Status" value="Bucket" />
        <input type="text" id="Bucket" ></input><br/>
      
        <input type="button" value="Show bucket" onclick="f();" /><br/>
    </form>