<template>
	<div>
		<el-menu default-active="2-1" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" :collapse="isCollapse" 
			background-color="#283238"
			text-color="#fff"
			active-text-color="#14988B">
			<el-submenu index="1">
				<template slot="title">
					<i class="material-icons">people</i>
					<span>User Management</span>
				</template>
				<el-menu-item v-for="(useroption, subIndex) in useroptions"
					:index="useroption.index"
					:key="useroption.key"
					route="useroption.url">
						<a :href="useroption.url">{{ useroption.name }}</a>
				</el-menu-item>
			</el-submenu>
			<el-submenu index="2">
				<template slot="title">
					<i class="material-icons">account_box</i>
					<span>{{ username }}</span>
				</template>
				<el-menu-item @click="onLogout()" index="5-1">Logout</el-menu-item>
			</el-submenu>			
		</el-menu>
	</div>
</template>

<script>
export default {
	props: {
		isCollapse: Boolean,
		username: String,
	},
	data() {
		return {
			useroptions: [
				{ 'index': '1-1', 'name' : 'Users', 'url' : '/users' },
			],		
		}
	},
	methods: {
		handleOpen(key, keyPath) {
			// console.log(key, keyPath);
		},
		handleClose(key, keyPath) {
			// console.log(key, keyPath);
		},
		onLogout() {
			axios.post('/logout')
			.then(response => {
				window.location = '/';
			})
			.catch(error => {
				console.log(error);
			})
		}
	}
}
</script>

<style scoped>
i {
	color: #fff;
}
a {
	color: #fff;
	text-decoration: none;
}
.el-menu-vertical-demo {
	height: 100vh;
}

.el-menu-vertical-demo:not(.el-menu--collapse) {
	width: 300px;
	min-height: 400px;
	height: 100vh;
}
</style>
