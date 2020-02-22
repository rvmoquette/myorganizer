
public class Task {

    // key
    private int code_task;

    // dependences
    private int code_user;

    // informations
    private String task_Name;
    private String task_Date_creation;
    private String task_Description;
    private int task_Workflow;

    public Task() { }

    public Task( int code_task,  int code_user,  String task_Name,  String task_Date_creation,  String task_Description,  int task_Workflow ) {
        this.code_task = code_task;
        this.code_user = code_user;
        this.task_Name = task_Name;
        this.task_Date_creation = task_Date_creation;
        this.task_Description = task_Description;
        this.task_Workflow = task_Workflow;
    }

    public int get_code_task() { return this.code_task; }
    public int get_code_user() { return this.code_user; }
    public String get_task_Name() { return this.task_Name; }
    public String get_task_Date_creation() { return this.task_Date_creation; }
    public String get_task_Description() { return this.task_Description; }
    public int get_task_Workflow() { return this.task_Workflow; }

    public void set_code_user( int code_user ) { this.code_user = code_user; }
    public void set_task_Name( String task_Name ) { this.task_Name = task_Name; }
    public void set_task_Date_creation( String task_Date_creation ) { this.task_Date_creation = task_Date_creation; }
    public void set_task_Description( String task_Description ) { this.task_Description = task_Description; }
    public void set_task_Workflow( int task_Workflow ) { this.task_Workflow = task_Workflow; }

}
