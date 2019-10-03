<template>
    
</template>

<script>
export default {
    name: "ImageShow",

    mounted()
    {
        axios.get('/api/imgs/1')
            .then( response => {

            })
            .catch( errors => {

            });
    }
}
</script>

<style scoped>

</style>