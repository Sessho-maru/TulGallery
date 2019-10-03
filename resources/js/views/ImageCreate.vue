<template>
    <div class="flex flex-1 flex-col">
        <form @submit.prevent="submitForm">
            <div class="pb-2">
                <label for="image" class="text-blue-500 pt-2 uppercase text-xs font-bold">Image</label>
                <input id="image" type="file" ref="file" accept=".jpeg,.png" class="pt-8 w-full border-b pb-2 focus:outline-none focus:border-blue-400" enctype="multipart/form-data" @change="fileHandle" @click="clearError"/>
            </div>

            <p id="error" class="text-red-600 text-sm"></p>

            <div class="py-8">
                <label for="description" class="text-blue-500 pt-2 uppercase text-xs font-bold">Description</label>
                <div class="pt-8">
                    <ckeditor id="description" type="classic" v-model="form.desc"></ckeditor>
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

    props: [
        'api_token'
    ],

    data()
    {
        return {
            file: "",

            form: {
                api_token: this.api_token,
                url: "",
                desc: ""
            },

            uploaded: false,
        }
    },

    methods: {

        fileHandle()
        {
            this.file = this.$refs.file.files[0];
            let extension = this.file.name.split('.').pop();

            if (extension != 'jpeg' && extension != 'png')
            {
                alert("not supported");
                this.file = null;
                return;
            }

            this.uploaded = true;
        },

        submitForm()
        {
            if (this.uploaded == false)
            {
                document.getElementById('error').innerHTML = "Require at least 1 Image";
                return;
            }

            let formData = new FormData();
            formData.append('file', this.file);

            axios.post('/imgs/upload', formData, { headers: { 'Content-Type': 'multipart/form-data' } })

                    .then( response => {
                        this.uploaded = true;
                        this.form.url = response.data.url;
                        console.log('SUCCESS!!');

                        axios.post('/api/imgs', this.form)
                                .then( response => {
                    
                                })
                                .catch( errors => {
                    
                                });
                                
                    })
                    
                    .catch( errors => {
                        console.log('FAILURE!!');
                    });
        },

        clearError()
        {
            document.getElementById('error').innerHTML = "";
        }
    }
}
</script>

<style lang="">

</style>