<!-- Add Task Modal -->
<div id="taskModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Task</h3>
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <input 
                            type="text" 
                            name="title" 
                            placeholder="Task title"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>
                    
                    <div>
                        <select 
                            name="category"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">Select category</option>
                            <option value="work">Work</option>
                            <option value="personal">Personal</option>
                            <option value="groceries">Groceries</option>
                            <option value="health">Health</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <select 
                            name="priority"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">Select priority</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Due Date</label>
                        <input 
                            type="date" 
                            name="due_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Reminder</label>
                        <input 
                            type="datetime-local" 
                            name="reminder"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button 
                            type="button"
                            onclick="closeModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
                        >
                            Add Task
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 