
public class ATaskLabel {

    // dependences
    private int code_task;
    private int code_label;

    // informations
    private boolean a_task_label_Link;

    public ATaskLabel() { }

    public ATaskLabel(  int code_task,  int code_label,  boolean a_task_label_Link ) {
        this.code_task = code_task;
        this.code_label = code_label;
        this.a_task_label_Link = a_task_label_Link;
    }

    public int get_code_task() { return this.code_task; }
    public int get_code_label() { return this.code_label; }
    public boolean get_a_task_label_Link() { return this.a_task_label_Link; }

    public void set_a_task_label_Link( boolean a_task_label_Link ) { this.a_task_label_Link = a_task_label_Link; }

}
