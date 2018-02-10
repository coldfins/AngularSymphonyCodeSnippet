package com.journaldev.spring.service;

import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.journaldev.spring.dao.StudentDAO;
import com.journaldev.spring.model.Student;

@Service
public class StudentServiceImpl implements StudentService {

	private StudentDAO studentDAO;

	@Autowired(required = true)
	public void setStudentDAO(StudentDAO studentDAO) {
		this.studentDAO = studentDAO;
	}

	@Override
	public void addStudent(Student student) {
		this.studentDAO.addStudent(student);
	}

	@Override
	public List<Student> listStudents() {
		return this.studentDAO.listStudents();
	}

	@Override
	public List<Student> checkStudents(String name) {
		return this.studentDAO.checkStudents(name);
	}
}