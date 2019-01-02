<template>
    <el-alert v-if="error"
    	:title="error"
    	type="error">
  	</el-alert>
</template>

<script>
    export default {
        props: {
			error: String
		},
    }
</script>
