<template>
  
</template>

<script>
export default {
    name: 'AllTags',

    mounted()
    {
        axios.get('/tags')
            .then( response => {
                console.log(response.data);
            })
            .catch( errors => {

            });
    }
}
</script>

<style scoped>

</style>