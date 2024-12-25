<template>
    <div>
      <h1>Calendar Task Manager</h1>
  
      <!-- Month-Year View -->
      <div class="calendar-header">
        <button @click="prevMonth">Previous</button>
        <span>{{ monthYear }}</span>
        <button @click="nextMonth">Next</button>
      </div>
  
      <div id="calendar" ref="calendar"></div>
  
      <!-- Modal for adding a task -->
      <div v-if="showModal" class="modal">
        <div class="modal-content">
          <span class="close" @click="closeModal">&times;</span>
          <h3>{{ selectedDate }}</h3>
          <input type="text" v-model="newTask" placeholder="Enter task" />
          <button @click="addTask">Add Task</button>
          
          <h4>Reminders:</h4>
          <ul>
            <li v-for="task in tasks" :key="task.id">
              <span :class="{ 'done': task.is_done }">
                <span @click="toggleTaskStatus(task)" class="status">
                  {{ task.is_done ? '✅' : '⭕️' }}
                </span>
                {{ task.content }}
              </span>
              <span @click="editTask(task)" class="edit">🖊</span>
              <span @click="deleteTask(task)" class="delete">🗑</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import flatpickr from "flatpickr";
  import axios from "axios";
  
  export default {
    data() {
      return {
        showModal: false,
        selectedDate: "",
        newTask: "",
        tasks: [],
        currentMonth: new Date().getMonth(), // 0-11
        currentYear: new Date().getFullYear(),
      };
    },
    computed: {
      monthYear() {
        const months = [
          "January", "February", "March", "April", "May", "June", 
          "July", "August", "September", "October", "November", "December"
        ];
        return `${months[this.currentMonth]} ${this.currentYear}`;
      }
    },
    methods: {
      initCalendar() {
        flatpickr(this.$refs.calendar, {
          inline: true,
          minDate: new Date(),
          dateFormat: "Y-m-d",
          onChange: (selectedDates) => {
            this.selectedDate = this.formatDate(selectedDates[0]);
            this.openModal();
          },
        });
      },
      formatDate(date) {
        const d = new Date(date);
        const month = String(d.getMonth() + 1).padStart(2, "0");
        const day = String(d.getDate()).padStart(2, "0");
        const year = d.getFullYear();
        return `${month}-${day}-${year}`;
      },
      openModal() {
        this.showModal = true;
        this.fetchTasks();
      },
      closeModal() {
        this.showModal = false;
        this.newTask = "";
      },
      async fetchTasks() {
        const response = await axios.get("/api/tasks", {
          params: { date: this.selectedDate },
        });
        this.tasks = response.data;
      },
      async addTask() {
        const response = await axios.post("/api/tasks", {
          task_date: this.selectedDate,
          task: this.newTask,
        });
        this.tasks.push(response.data);
        this.newTask = "";
      },
      async toggleTaskStatus(task) {
        const response = await axios.patch(`/api/tasks/${task.id}`, {
          is_done: !task.is_done,
        });
        task.is_done = response.data.is_done;
      },
      async editTask(task) {
        const newContent = prompt("Edit task:", task.content);
        if (newContent !== null && newContent !== "") {
          const response = await axios.patch(`/api/tasks/${task.id}`, {
            content: newContent,
          });
          task.content = response.data.content;
        }
      },
      async deleteTask(task) {
        const confirmDelete = confirm("Are you sure you want to delete this task?");
        if (confirmDelete) {
          await axios.delete(`/api/tasks/${task.id}`);
          this.tasks = this.tasks.filter(t => t.id !== task.id);
        }
      },
      prevMonth() {
        if (this.currentMonth === 0) {
          this.currentMonth = 11;
          this.currentYear--;
        } else {
          this.currentMonth--;
        }
        this.initCalendar();
      },
      nextMonth() {
        if (this.currentMonth === 11) {
          this.currentMonth = 0;
          this.currentYear++;
        } else {
          this.currentMonth++;
        }
        this.initCalendar();
      },
    },
    mounted() {
      this.initCalendar();
    },
  };
  </script>
  
  <style scoped>
  /* Modal Styling */
  .modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .modal-content {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 300px;
    text-align: center;
  }
  
  .close {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
  }
  
  .status {
    cursor: pointer;
    font-size: 18px;
  }
  
  .done {
    text-decoration: line-through;
  }
  
  .edit, .delete {
    cursor: pointer;
    margin-left: 10px;
  }
  
  /* Calendar Header */
  .calendar-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
  }
  
  button {
    padding: 5px 10px;
    cursor: pointer;
  }
  </style>
  