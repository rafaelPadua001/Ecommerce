<template>
    <v-row fluid>
        <v-col class="d-flex justify-center flex-column" cols="auto" sm="12">
            <div>
                <v-file-input
                    v-model="image"
                    label="image"
                    :rules="rules"
                    accept="image/png, image/jpg, image/jpeg, image/bmp"
                    placeholder="pick an avatar"
                    prepend-icon="fas fa-camera"
                ></v-file-input>
                <v-btn 
                    :loading="loading"
                    class="flex-grow-1"
                    variant="text"
                    color="success"
                    @click="load"
                >
                    Upload file 
                </v-btn>
            </div>
            
        </v-col>
    </v-row>
    
</template>

<script>
    export default{
        data: () => ({
            image: [],
            loading: false,
            rules: [
                value => {
                    return !value || !value.length || value[0].size < 2000000 || 'Avatar size should be less than 2 Mb !'
                },
            ],
        }),
        methods: {
            uploadFile(){
                const data = {
                    image: this.image,
                };
                axios.post('/profileImage/upload', data,
                {
                    headers: {
                        'Content-Type' : 'multipart/form-data'
                    }
                })
                .then((response) => {
                 
                    this.$emit('update-avatar', response);
                    this.$emit('close-dialog');
                    return this.image.push(response.data);
                    
                })
                .catch((response) => {
                    return alert('Error :' + response);
                });
                
            },
            load(){
                this.loading = true;
                setTimeout(() => {
                    this.loading = false;
                    return this.uploadFile();
                }, 3000);
            },
            
        }
    }
</script>