<template>
    <div class="w-full h-full" style="pointer-events: none;">
        <div v-bind="getRootProps()"
             class="relative h-full w-full py-12 w-full text-center flex flex-col justify-center"
        >
            <Heading :level="1">
                {{ __('Drop your files here!') }}
            </Heading>
            <input v-bind="getInputProps()" />
        </div>
        <UploadProgress
            ref="uploader"
            :current="currentPath"
            @remove-file="$emit('refreshFiles')"
        ></UploadProgress>
    </div>
</template>

<script>
import { useDropzone } from "vue3-dropzone";
import UploadProgress from "./UploadProgress";
import { ref, onMounted } from "vue";

const token = document.head.querySelector('meta[name="csrf-token"]');

export default {
    components: {
        UploadProgress,
    },
    props: ['currentPath'],
    setup(props, { emit }) {
        const uploader = ref(null);
        let uploadIns = uploader;
        onMounted(() => {
            uploadIns = uploader.value;
        });

        const saveFiles = async (files) => {
            const filesArr = [];
            for (let x = 0; x < files.length; x++) {
                filesArr.push({file: files[x]});
            }
            await uploadIns.startUploadingFiles(filesArr, 'files');
        };

        function onDrop(acceptFiles, rejectReasons) {
            saveFiles(acceptFiles); // saveFiles as callback
            console.log(rejectReasons);
        }

        const { getRootProps, getInputProps, isDragActive, rest } = useDropzone({
            multiple: true,
            onDrop,
            disabled: false,
        });

        return {
            getRootProps,
            getInputProps,
            isDragActive,
            ...props,
            uploader,
            ...rest,
        };
    },
};
</script>

<style>
.h-16 {
    height: 4rem;
}
</style>
