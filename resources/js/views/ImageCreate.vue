<template>
    <div class="overflow-x-hidden">
        <div class="flex flex-1 flex-col">

            <form @submit.prevent="submitForm">
                <div class="pb-2">
                    <label for="image" class="text-blue-500 pt-2 uppercase text-xs font-bold">Image</label>
                    <input id="image" type="file" ref="file" accept=".jpeg,.jpg,.png" class="pt-5 w-full border-b pb-2 focus:outline-none focus:border-blue-400" enctype="multipart/form-data" @change="fileHandle" @click="clearError('error_image')"/>
                </div>
                <p id="error_image" class="text-red-600 text-sm font-bold"></p>

                <div class="py-8">
                    <label for="description" class="text-blue-500 pt-2 uppercase text-xs font-bold">Description</label>
                    <div class="pt-5">
                        <ckeditor id="description" type="classic" v-model="form.description"></ckeditor>
                    </div>
                </div>

                <div class="pb-2">
                    <label for="tag" class="text-blue-500 pt-2 uppercase text-xs font-bold">Tags</label>
                    <input id="tag" type="text" v-model="form.tags" class="pt-5 w-full border-b pb-2 focus:outline-none focus:border-blue-400" @input="clearError('error_tag')">
                </div>
                <p id="error_tag" class="text-red-600 text-sm font-bold"></p>
            
                <div class="flex justify-end pt-8">
                    <button class="py-2 px-4 text-red-700 border rounded mr-5 hover:border-red-700">Cancel</button>
                    <button class="bg-blue-500 py-2 px-4 text-white rounded hover:bg-blue-400">Add New Image</button>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
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
                url: "http://via.placeholder.com/350x150",
                description: "",
                tags: "",
            },

            loaded: false,
        }
    },

    methods: {

        fileHandle()
        {
            this.file = this.$refs.file.files[0];
            let extension = this.file.name.split('.').pop();

            if (extension != 'jpeg' && extension != 'jpg' && extension != 'png')
            {
                alert("not supported");
                this.file = null;
                return;
            }

            this.loaded = true;
        },

        submitForm()
        {
            if (this.loaded == false)
            {
                document.getElementById('error_image').innerHTML = "Require at least 1 Image";
                return;
            }

            if (this.form.tags === "")
            {
                document.getElementById('error_tag').innerHTML = "Require at least 1 Tag";
                return;
            }

            let tags = this.form.tags.split(",");
            if (this.hasDuplicates(tags) == true)
            {
                document.getElementById('error_tag').innerHTML = "Inserted Tags aren't unique";
                return;
            }

            this.form.tags = this.form.tags.concat(",");

            axios.post('/api/imgs', {...this.form, api_token: this.api_token}) // Image form request
                    .then( response => {

                        let image_id = response.data.data.image_id;
                    
                        let formData = new FormData();
                        formData.append('file', this.file);

                        axios.post('/imgs/upload', formData, { headers: { 'Content-Type': 'multipart/form-data' } }) // Image upload request
                            .then( response => {
                                axios.patch('/api/imgs/' + image_id, { 'api_token': this.api_token, 'url': response.data.url, 'flag': "url_update" }) // Url update request
                                        .then( resopnse => {
                                            this.$router.push(resopnse.data.links.self);
                                        })
                                        .catch( errors => {

                                        });
                            })
                            .catch( errors => {
                                
                            });
                    })
                    .catch( errors => {
                        document.getElementById('error_tag').innerHTML = errors.response.data.msg;
                    });
        },

        hasDuplicates(array) {
            return (new Set(array)).size !== array.length;
        },

        clearError(field)
        {
            document.getElementById(field).innerHTML = "";
        }
    }
}
</script>

<style lang="">

</style>