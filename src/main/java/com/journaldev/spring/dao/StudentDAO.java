package com.journaldev.spring.dao;

import java.util.List;

import com.journaldev.spring.model.Student;

public interface StudentDAO {

	public void addStudent(Student student);

	public List<Student> checkStudents(String name);

	public List<Student> listStudents();
}
