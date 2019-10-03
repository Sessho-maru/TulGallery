<template>
    <div class="flex flex-1 flex-col">
        <form @submit.prevent="submitForm">
            <div class="pb-8">
                <label for="image" class="text-blue-500 pt-2 uppercase text-xs font-bold">Image</label>
                <input id="image" type="file" ref="file" accept=".jpeg,.png" class="pt-8 w-full border-b pb-2 focus:outline-none focus:border-blue-400" enctype="multipart/form-data"/>
            </div>

            <div class="pb-4">
                <label for="description" class="text-blue-500 pt-2 uppercase text-xs font-bold">Description</label>
                <div class="pt-8">
                    <ckeditor id="description" type="classic" v-model="desc"></ckeditor>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button class="py-2 px-4 text-red-700 border rounded mr-5 hover:border-red-700">Cancel</button>
                <button class="bg-blue-500 py-2 px-4 text-white rounded hover:bg-blue-400">Add New Image</button>
            </div>
        </form>
    </div>
</template>

<script>
import InputField from '../components/InputField';

export default {
    name: "ImageCreate",

    data()
    {
        return {
            file: "",

            form: {
                url: "",
                desc: ""
            }
        }
    },

    methods: {

        submitForm()
        {
            this.file = this.$refs.file.files[0];
            let extension = this.file.name.split('.').pop();

            if (extension != 'jpeg' && extension != 'png')
            {
                alert("not supported");
                return;
            }

            let formData = new FormData();
            formData.append('file', this.file);

            axios.post('/imgs/upload', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then( response => {
                        this.url = response.data.url;
                        console.log('SUCCESS!!');
                    })
                    .catch( errors => {
                        console.log('FAILURE!!');
                    });
            
            axios.post('/imgs/create', this.form)
                .then( response => {
                    
                })
                .catch( errors => {

                });
        }
    }
}
</script>

<style lang="">

</style>