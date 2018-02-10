package com.journaldev.spring;

import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.json.JSONArray;
import org.json.JSONObject;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.ResponseBody;

import com.journaldev.spring.model.Student;
import com.journaldev.spring.service.StudentService;

@Controller
public class StudentController {

	@Autowired
	private StudentService studentService;

	@Autowired(required = true)
	public void setStudentService(StudentService studentService) {
		this.studentService = studentService;
	}

	@RequestMapping(value = "/", method = RequestMethod.GET)
	public String defaultMethod(Model model) {
		return "redirect:/pages/addstudent/addStudentView.html";
	}

	@RequestMapping(value = "/addStudent", method = RequestMethod.POST)
	public @ResponseBody String addStudent(HttpServletRequest request,
			HttpServletResponse response, Model model) {
		String name = request.getParameter("name");
		String email = request.getParameter("email");
		String mobile = request.getParameter("mobile");
		if(null != name && "" != name && null != email && "" != email){
			List<Student> students = studentService.checkStudents(name);
			if(students.size() <= 0){
				Student student = new Student();
				student.setName(name);
				student.setEmail(email);
				student.setMobile(mobile);
				studentService.addStudent(student);							
			}
		}
		
		String jsonString = studentList();
		return jsonString;
	}
	
	@RequestMapping(value = "/getStudent", method = RequestMethod.GET)
	public @ResponseBody String getStudent(HttpServletRequest request,
			HttpServletResponse response, Model model) {
		String jsonString = studentList();
		return jsonString;
	}

	public String studentList() {
		List<Student> student = studentService.listStudents();
		JSONArray jsonArray = new JSONArray();
		for(int i=0;i<student.size();i++){
			try{
				JSONObject jsonObject = new JSONObject();
				jsonObject.put("name", student.get(i).getName());
				jsonObject.put("email", student.get(i).getEmail());
				jsonObject.put("mobile", student.get(i).getMobile());
				jsonArray.put(jsonObject);
			}catch(Exception e){
				e.printStackTrace();
			}
		}
		return jsonArray.toString();
	}

}