
public class AUserTask {

    // dependences
    private int code_user;
    private int code_task;

    // informations
    private boolean a_user_task_Link;

    public AUserTask() { }

    public AUserTask(  int code_user,  int code_task,  boolean a_user_task_Link ) {
        this.code_user = code_user;
        this.code_task = code_task;
        this.a_user_task_Link = a_user_task_Link;
    }

    public int get_code_user() { return this.code_user; }
    public int get_code_task() { return this.code_task; }
    public boolean get_a_user_task_Link() { return this.a_user_task_Link; }

    public void set_a_user_task_Link( boolean a_user_task_Link ) { this.a_user_task_Link = a_user_task_Link; }

}
